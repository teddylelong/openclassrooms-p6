<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Repository\CommentRepository;

class CommentManager
{
    private $commentRepository;

    /**
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param $status
     * @return int
     */
    public function countAllByStatus($status = Comment::STATUS_ACCEPTED): int
    {
        return $this->commentRepository->countAllByStatus($status);
    }

    /**
     * @param Figure $figure
     * @param $status
     * @return int
     */
    public function countAllByFigureAndStatus(Figure $figure, $status = Comment::STATUS_ACCEPTED): int
    {
        return $this->commentRepository->countAllByFigureAndStatus($figure, $status);
    }

    /**
     * @param Comment $comment
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function add(Comment $comment)
    {
        $this->commentRepository->add($comment);
    }

    /**
     * @return Comment[]
     */
    public function findAll()
    {
        return $this->commentRepository->findAll();
    }

    /**
     * @param $status
     * @return Comment[]
     */
    public function findAllByStatus($status = Comment::STATUS_PENDING)
    {
        return $this->commentRepository->findAllByStatus($status);
    }

    /**
     * @param Figure $figure
     * @param $status
     * @return Comment[]
     */
    public function findByFigureAndStatus(Figure $figure, $status = Comment::STATUS_ACCEPTED)
    {
        return $this->commentRepository->findByFigureAndStatus($figure, $status);
    }

    /**
     * @param Figure $figure
     * @param $status
     * @param int $max
     * @param int $offset
     * @return float|int|mixed|string
     */
    public function findAllByFigureAndStatusLimit(Figure $figure, $status = Comment::STATUS_ACCEPTED, int $max = 10, int $offset = 0)
    {
        return $this->commentRepository->findAllByFigureAndStatusLimit($figure, $status, $max, $offset);
    }

    /**
     * @param Figure $figure
     * @param $status
     * @param int $max
     * @param int $offset
     * @return Comment[]
     */
    public function findByFigureAndStatusOrderByDateLimit(Figure $figure, $status = Comment::STATUS_ACCEPTED, int $max = 10, int $offset = 0)
    {
        return $this->commentRepository->findByFigureAndStatusOrderByDateLimit($figure, $status, $max, $offset);
    }

    /**
     * @param Comment $comment
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Comment $comment)
    {
        $this->commentRepository->remove($comment);
    }

    /**
     * @param string $status
     * @return array|null
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