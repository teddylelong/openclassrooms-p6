<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Repository\CommentRepository;

class CommentManager
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function add(Comment $comment)
    {
        $this->commentRepository->add($comment);
    }

    public function findAll()
    {
        return $this->commentRepository->findAll();
    }

    public function findAllByStatus($status = Comment::STATUS_PENDING)
    {
        return $this->commentRepository->findAllByStatus($status);
    }

    public function findByFigureAndStatus(Figure $figure, $status = Comment::STATUS_ACCEPTED)
    {
        return $this->commentRepository->findByFigureAndStatus($figure, $status);
    }

    public function delete(Comment $comment)
    {
        $this->commentRepository->remove($comment);
    }
}