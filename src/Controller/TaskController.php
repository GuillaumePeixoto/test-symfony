<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/tasks', name: 'task_')]
class TaskController extends AbstractController
{
    private $entityManager;

    // Injecter l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /*
        Cette méthode permet de récupérer toutes les tâches
    */
    #[Route('/get-all', name: 'get-all', methods: ['GET'])]
    public function getTasks(TaskRepository $taskRepository, SerializerInterface $serializer): Response
    {
        $tasks = $taskRepository->findAll();
        $data = $serializer->serialize($tasks, 'json', ['Groups' => 'task:read']);

        print_r($data);
        die();

        return new Response(
            $data,
            Response::HTTP_CREATED,
            ['Content-Type' => 'application/json']
        );
    }

    /*
        Cette méthode permet de créer une tâche
    */
    #[Route('/create', name: 'create_task', methods: ['POST'])]
    public function createTask(Request $request, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data['titre']) || empty($data['status'])) {
            return new Response('Missing required data', Response::HTTP_BAD_REQUEST);
        }
        
        $task = new Task();
        $task->setTitre($data['titre']);
        $task->setStatus($data['status']);

        if(!empty($data['description'])) {
            $task->setDescription($data['description']);
        }

        try {
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            // Si l'enregistrement se passe bien
            return new Response(
                json_encode(['success' => true]),
                Response::HTTP_CREATED,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            // Si une erreur survient lors de l'enregistrement
            return new Response(
                json_encode(['success' => false, 'error' => $e->getMessage()]),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    /*
        Cette méthode permet de modifier une tâche
    */
    #[Route('/update/{id}', name: 'update_task', methods: ['PUT'])]
    public function updateTask(
        $id,
        Request $request,
        TaskRepository $taskRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager // Injection de dépendance pour l'EntityManager
    ): Response
    {
        // Récupérer la tâche par son ID
        $task = $taskRepository->find($id);

        if (!$task) {
            return new Response('Task not found', Response::HTTP_NOT_FOUND);
        }

        // Décoder les données JSON envoyées dans la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier si chaque champ est présent et non vide avant de mettre à jour
        if (!empty($data['titre'])) {
            $task->setTitre($data['titre']);
        }

        if (!empty($data['description'])) {
            $task->setDescription($data['description']);
        }

        if (!empty($data['status'])) {
            $task->setStatus($data['status']);
        }


        try {
            $entityManager->flush();

            // Si l'enregistrement se passe bien
            return new Response(
                json_encode(['success' => true]),
                Response::HTTP_CREATED,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            // Si une erreur survient lors de l'enregistrement
            return new Response(
                json_encode(['success' => false, 'error' => $e->getMessage()]),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }
    }

    /*
        Cette méthode permet de supprimer une tâche
    */
    #[Route('/delete/{id}', name: 'delete_task', methods: ['DELETE'])]
    public function deleteTask($id, TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $task = $taskRepository->find($id);
        if (!$task) {
            return new Response('Task not found', Response::HTTP_NOT_FOUND);
        }

        try{
            $entityManager->remove($task);
            $entityManager->flush();
             // Si la suppression se passe bien
             return new Response(
                json_encode(['success' => true]),
                Response::HTTP_CREATED,
                ['Content-Type' => 'application/json']
            );
        } catch (\Exception $e) {
            return new Response(
                json_encode(['success' => false, 'error' => $e->getMessage()]),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['Content-Type' => 'application/json']
            );
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
