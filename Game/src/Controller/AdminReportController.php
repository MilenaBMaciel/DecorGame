<?php

namespace App\Controller;

use App\Repository\FurnitureRepository;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

final class AdminReportController extends AbstractController
{
    // Envia para quem clicou um email com o relatório (salas e móveis) em JSON anexo.
    // POST + token CSRF: um link <a> seria disparado pelo prefetch do Turbo só de passar o mouse.
    #[Route('/admin/report', name: 'app_admin_report', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Only admins can send the report.')]
    public function send(
        Request $request,
        RoomRepository $roomRepository,
        FurnitureRepository $furnitureRepository,
        SerializerInterface $serializer,
        MailerInterface $mailer,
    ): Response {
        if (!$this->isCsrfTokenValid('admin_report', $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $rooms = $roomRepository->findAllOrdered();
        $furnitureItems = $furnitureRepository->findAll();

        // Mesmo grupo da API (/api/rooms e /api/furniture): o anexo tem exatamente os mesmos campos
        $json = $serializer->serialize([
            'generated_at' => new \DateTimeImmutable(),
            'rooms' => $rooms,
            'furniture' => $furnitureItems,
        ], 'json', [
            'groups' => 'api_report',
            'json_encode_options' => \JSON_PRETTY_PRINT | \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES,
        ]);

        $email = (new Email())
            ->from('gamedecor136@gmail.com')
            ->to($this->getUser()->getUserIdentifier())
            ->subject('DecorGame - Rooms and furniture report')
            ->text(\sprintf(
                "Hello!\n\nAttached is the DecorGame report: %d room(s) and %d furniture item(s).\n",
                \count($rooms),
                \count($furnitureItems),
            ))
            ->attach($json, 'decorgame-report.json', 'application/json');

        try {
            $mailer->send($email);
            $this->addFlash('success', 'Report sent to '.$this->getUser()->getUserIdentifier().'!');
        } catch (TransportExceptionInterface) {
            // Ex.: servidor de email (Mailpit) desligado
            $this->addFlash('error', 'The email could not be sent. Is the mail server running?');
        }

        return $this->redirectToRoute('app_menu_edit', [], Response::HTTP_SEE_OTHER);
    }
}
