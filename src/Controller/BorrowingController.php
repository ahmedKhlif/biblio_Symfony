<?php

namespace App\Controller;

use App\Entity\BookReservation;
use App\Entity\Livre;
use App\Entity\Loan;
use App\Form\BorrowingRequestType;
use App\Repository\BookReservationRepository;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/borrowing')]
#[IsGranted('ROLE_USER')]
final class BorrowingController extends AbstractController
{
    #[Route('/request/{id}', name: 'app_borrowing_request', methods: ['GET', 'POST'])]
    public function request(
        Livre $livre,
        Request $request,
        EntityManagerInterface $entityManager,
        BookReservationRepository $reservationRepository
    ): Response {
        // Check if user already has an active loan or reservation for this book
        $user = $this->getUser();
        $existingLoan = $entityManager->getRepository(Loan::class)->findOneBy([
            'user' => $user,
            'livre' => $livre,
            'status' => [Loan::STATUS_REQUESTED, Loan::STATUS_APPROVED, Loan::STATUS_ACTIVE]
        ]);

        $existingReservation = $reservationRepository->findUserActiveReservationForBook($user, $livre);

        if ($existingLoan || $existingReservation) {
            $this->addFlash('warning', 'Vous avez déjà une demande en cours pour ce livre.');
            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }

        // Check if book is available
        if ($livre->isAvailableForBorrowing()) {
            // Create loan request
            $loan = new Loan();
            $loan->setUser($user);
            $loan->setLivre($livre);

            $form = $this->createForm(BorrowingRequestType::class, $loan);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($loan);
                $entityManager->flush();

                $this->addFlash('success', 'Votre demande d\'emprunt a été soumise avec succès.');
                return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
            }

            return $this->render('borrowing/request.html.twig', [
                'livre' => $livre,
                'form' => $form,
                'available' => true,
            ]);
        } else {
            // Book not available - add to reservation list
            $reservation = new BookReservation();
            $reservation->setUser($user);
            $reservation->setLivre($livre);

            // Calculate position
            $activeReservations = $reservationRepository->findActiveReservationsForBook($livre);
            $position = count($activeReservations) + 1;
            $reservation->setPosition($position);

            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('info', 'Le livre n\'est pas disponible actuellement. Vous avez été ajouté à la liste d\'attente (position ' . $position . ').');

            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }
    }

    #[Route('/calendar/{id}', name: 'app_borrowing_calendar', methods: ['GET'])]
    public function calendar(Livre $livre): Response
    {
        $user = $this->getUser();

        // Get user's borrowing history for this book
        $userLoans = $livre->getLoans()->filter(function (Loan $loan) use ($user) {
            return $loan->getUser() === $user;
        });

        $events = [];
        foreach ($userLoans as $loan) {
            if ($loan->getLoanStartDate() && $loan->getDueDate()) {
                $events[] = [
                    'title' => 'Emprunt - ' . $loan->getStatusLabel(),
                    'start' => $loan->getLoanStartDate()->format('Y-m-d'),
                    'end' => $loan->getDueDate()->format('Y-m-d'),
                    'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                    'borderColor' => $this->getStatusColor($loan->getStatus()),
                ];
            }
        }

        return $this->render('borrowing/calendar.html.twig', [
            'livre' => $livre,
            'events' => json_encode($events),
        ]);
    }

    #[Route('/my-calendar', name: 'app_borrowing_my_calendar', methods: ['GET'])]
    public function myCalendar(LoanRepository $loanRepository): Response
    {
        $user = $this->getUser();
        $loans = $loanRepository->findBy(['user' => $user]);

        $events = [];
        foreach ($loans as $loan) {
            // Create events based on loan status
            switch ($loan->getStatus()) {
                case Loan::STATUS_ACTIVE:
                    // For active loans, show the loan period
                    if ($loan->getLoanStartDate() && $loan->getDueDate()) {
                        $events[] = [
                            'title' => $loan->getLivre()->getTitre() . ' - ' . $loan->getStatusLabel(),
                            'start' => $loan->getLoanStartDate()->format('Y-m-d'),
                            'end' => $loan->getDueDate()->format('Y-m-d'),
                            'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                            'borderColor' => $this->getStatusColor($loan->getStatus()),
                            'url' => $this->generateUrl('app_livre_show', ['id' => $loan->getLivre()->getId()]),
                        ];
                    }
                    break;

                case Loan::STATUS_REQUESTED:
                    // For requested loans, show the request date
                    $events[] = [
                        'title' => $loan->getLivre()->getTitre() . ' - ' . $loan->getStatusLabel(),
                        'start' => $loan->getRequestedAt()->format('Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                        'borderColor' => $this->getStatusColor($loan->getStatus()),
                        'url' => $this->generateUrl('app_livre_show', ['id' => $loan->getLivre()->getId()]),
                    ];
                    break;

                case Loan::STATUS_APPROVED:
                    // For approved loans, show approval date and estimated due date
                    if ($loan->getApprovedAt()) {
                        $events[] = [
                            'title' => $loan->getLivre()->getTitre() . ' - ' . $loan->getStatusLabel(),
                            'start' => $loan->getApprovedAt()->format('Y-m-d'),
                            'allDay' => true,
                            'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                            'borderColor' => $this->getStatusColor($loan->getStatus()),
                            'url' => $this->generateUrl('app_livre_show', ['id' => $loan->getLivre()->getId()]),
                        ];
                    }
                    break;

                case Loan::STATUS_RETURNED:
                    // For returned loans, show the return date
                    if ($loan->getReturnedAt()) {
                        $events[] = [
                            'title' => $loan->getLivre()->getTitre() . ' - ' . $loan->getStatusLabel(),
                            'start' => $loan->getReturnedAt()->format('Y-m-d'),
                            'allDay' => true,
                            'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                            'borderColor' => $this->getStatusColor($loan->getStatus()),
                            'url' => $this->generateUrl('app_livre_show', ['id' => $loan->getLivre()->getId()]),
                        ];
                    }
                    break;

                case Loan::STATUS_OVERDUE:
                    // For overdue loans, show from start date to today
                    if ($loan->getLoanStartDate()) {
                        $events[] = [
                            'title' => $loan->getLivre()->getTitre() . ' - ' . $loan->getStatusLabel(),
                            'start' => $loan->getLoanStartDate()->format('Y-m-d'),
                            'end' => (new \DateTimeImmutable())->format('Y-m-d'),
                            'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                            'borderColor' => $this->getStatusColor($loan->getStatus()),
                            'url' => $this->generateUrl('app_livre_show', ['id' => $loan->getLivre()->getId()]),
                        ];
                    }
                    break;

                default:
                    // For other statuses, show request date
                    $events[] = [
                        'title' => $loan->getLivre()->getTitre() . ' - ' . $loan->getStatusLabel(),
                        'start' => $loan->getRequestedAt()->format('Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => $this->getStatusColor($loan->getStatus()),
                        'borderColor' => $this->getStatusColor($loan->getStatus()),
                        'url' => $this->generateUrl('app_livre_show', ['id' => $loan->getLivre()->getId()]),
                    ];
                    break;
            }
        }

        // Ensure we always pass a valid JSON array
        $eventsJson = json_encode($events ?: []);

        return $this->render('borrowing/my_calendar.html.twig', [
            'events' => $eventsJson,
        ]);
    }

    private function getStatusColor(string $status): string
    {
        return match ($status) {
            Loan::STATUS_ACTIVE => '#28a745', // green
            Loan::STATUS_OVERDUE => '#dc3545', // red
            Loan::STATUS_RETURNED => '#6c757d', // gray
            Loan::STATUS_REQUESTED => '#ffc107', // yellow
            Loan::STATUS_APPROVED => '#17a2b8', // blue
            default => '#6c757d', // gray
        };
    }
}