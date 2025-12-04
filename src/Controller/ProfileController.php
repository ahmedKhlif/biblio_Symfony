<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {}

    #[Route('', name: 'app_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle profile picture upload
            $profilePictureFile = $form->get('profilePicture')->getData();
            
            if ($profilePictureFile) {
                try {
                    $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $this->slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                    // Delete old picture if exists
                    if ($user->getProfilePicture()) {
                        $oldPath = $this->getParameter('profile_pictures_directory') . '/' . $user->getProfilePicture();
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $uploadDir = $this->getParameter('profile_pictures_directory');
                    
                    // Ensure directory exists and is writable
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $profilePictureFile->move($uploadDir, $newFilename);
                    $user->setProfilePicture($newFilename);
                    
                    $this->addFlash('success', 'Photo de profil mise à jour avec succès.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
                    error_log('Profile picture upload error: ' . $e->getMessage());
                }
            }

            // Handle billing address
            $billingAddressData = $request->request->get('billingAddressForm');
            if (is_array($billingAddressData)) {
                // Filter out empty values
                $billingAddress = array_filter($billingAddressData, fn($value) => !empty($value));
                if (!empty($billingAddress)) {
                    $user->setBillingAddress($billingAddress);
                    // Force Doctrine to detect changes on JSON column
                    $this->entityManager->getUnitOfWork()->propertyChanged($user, 'billingAddress', $user->getBillingAddress(), $billingAddress);
                }
            }

            // Handle shipping address
            $shippingAddressData = $request->request->get('shippingAddressForm');
            if (is_array($shippingAddressData)) {
                // Filter out empty values
                $shippingAddress = array_filter($shippingAddressData, fn($value) => !empty($value));
                if (!empty($shippingAddress)) {
                    $user->setShippingAddress($shippingAddress);
                    // Force Doctrine to detect changes on JSON column
                    $this->entityManager->getUnitOfWork()->propertyChanged($user, 'shippingAddress', $user->getShippingAddress(), $shippingAddress);
                }
            }

            // Profile update successful
            $this->entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('app_profile');
        }

        // Get user statistics
        $stats = [
            'totalOrders' => $user->getTotalOrdersCount(),
            'completedOrders' => $user->getCompletedOrdersCount(),
            'totalSpent' => $user->getTotalSpent(),
            'activeLoans' => $user->getActiveLoans()->count(),
            'wishlistCount' => $user->getWishlist()->count(),
            'ownedBooksCount' => $user->getPurchasedBooks()->count(),
            'readingGoalsCount' => $user->getReadingGoals()->count(),
        ];

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'stats' => $stats,
        ]);
    }

    #[Route('/orders', name: 'app_profile_orders', methods: ['GET'])]
    public function orders(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $orders = $user->getOrders();

        return $this->render('profile/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/loans', name: 'app_profile_loans', methods: ['GET'])]
    public function loans(): Response
    {
        // Redirect to the main loan management page to avoid duplication
        return $this->redirectToRoute('app_loan_index');
    }

    #[Route('/wishlist', name: 'app_profile_wishlist', methods: ['GET'])]
    public function wishlist(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $wishlist = $user->getWishlist();

        return $this->render('profile/wishlist.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }

    #[Route('/owned-books', name: 'app_profile_owned_books', methods: ['GET'])]
    public function ownedBooks(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $ownedBooks = $user->getPurchasedBooks();

        return $this->render('profile/owned_books.html.twig', [
            'user' => $user,
            'ownedBooks' => $ownedBooks,
        ]);
    }
}