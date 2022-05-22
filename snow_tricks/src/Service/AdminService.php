<?php

namespace App\Service;

class AdminService
{
    private $commentManager;

    public function __construct(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
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

}