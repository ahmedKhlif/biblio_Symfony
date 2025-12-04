<?php

namespace App\Repository;

use App\Entity\Loan;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Loan>
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    /**
     * Find active loans for user
     */
    public function findActiveLoansForUser(User $user): array
    {
        return $this->findBy([
            'user' => $user,
            'status' => [Loan::STATUS_ACTIVE, Loan::STATUS_OVERDUE]
        ]);
    }

    /**
     * Find overdue loans
     */
    public function findOverdueLoans(): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.status = :status')
            ->andWhere('l.dueDate < :now')
            ->setParameter('status', Loan::STATUS_ACTIVE)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getResult();
    }

    /**
     * Find loans by status
     */
    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status], ['requestedAt' => 'DESC']);
    }

    /**
     * Get loans statistics
     */
    public function getLoansStatistics(): array
    {
        $stats = [];

        // Count by status
        $statusCounts = $this->createQueryBuilder('l')
            ->select('l.status, COUNT(l.id) as count')
            ->groupBy('l.status')
            ->getQuery()
            ->getResult();

        foreach ($statusCounts as $stat) {
            $stats['status_' . $stat['status']] = (int) $stat['count'];
        }

        // Overdue count
        $overdueCount = $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.status = :active')
            ->andWhere('l.dueDate < :now')
            ->setParameter('active', Loan::STATUS_ACTIVE)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getSingleScalarResult();

        $stats['overdue'] = (int) $overdueCount;

        return $stats;
    }

    /**
     * Find loans due soon (within 3 days)
     */
    public function findLoansDueSoon(int $days = 3): array
    {
        $dueDate = new \DateTimeImmutable("+{$days} days");

        return $this->createQueryBuilder('l')
            ->where('l.status = :status')
            ->andWhere('l.dueDate <= :dueDate')
            ->andWhere('l.dueDate >= :now')
            ->setParameter('status', Loan::STATUS_ACTIVE)
            ->setParameter('dueDate', $dueDate)
            ->setParameter('now', new \DateTimeImmutable())
            ->orderBy('l.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}