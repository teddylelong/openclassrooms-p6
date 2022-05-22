<?php

namespace App\Service;

use App\Entity\Figure;

class AdminService
{
    private $commentManager;
    private $figureManager;

    public function __construct(CommentManager $commentManager, FigureManager $figureManager)
    {
        $this->commentManager = $commentManager;
        $this->figureManager = $figureManager;
    }

    public function countPendingComments(): int
    {
        $pendingCommentsCount = $this->commentManager->findAllByStatus();

        $count = 0;
        if ($pendingCommentsCount) {
            $count = count($pendingCommentsCount);
        }

        return $count;
    }

    public function countPendingFigures(): int
    {
        $pendingFiguresCount = $this->figureManager->findByStatusOrderByDate(Figure::STATUS_PENDING);

        $count = 0;
        if ($pendingFiguresCount) {
            $count = count($pendingFiguresCount);
        }

        return $count;
    }

}