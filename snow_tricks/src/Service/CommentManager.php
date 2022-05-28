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

    /**
     * Check a Comment status and return right Const
     */
    public function checkStatus(string $status): ?array
    {
        switch ($status) {
            case 'accept':
                return [
                    'status' => Comment::STATUS_ACCEPTED,
                    'label' => "validé"
                ];

            case 'refuse':
                return [
                    'status' => Comment::STATUS_REJECTED,
                    'label' => "refusé"
                ];

            case 'pending':
                return [
                    'status' => Comment::STATUS_PENDING,
                    'label' => "mis en file d'attente"
                ];

            default:
                return null;
        }
    }
}