<?php

namespace App\Service;

use App\Entity\Figure;

class AdminService
{
    private $commentManager;
    private $figureManager;

    /**
     * @param CommentManager $commentManager
     * @param FigureManager $figureManager
     */
    public function __construct(CommentManager $commentManager, FigureManager $figureManager)
    {
        $this->commentManager = $commentManager;
        $this->figureManager = $figureManager;
    }

    /**
     * @return int
     */
    public function countPendingComments(): int
    {
        $pendingCommentsCount = $this->commentManager->findAllByStatus();

        $count = 0;
        if ($pendingCommentsCount) {
            $count = count($pendingCommentsCount);
        }

        return $count;
    }

    /**
     * @return int
     */
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