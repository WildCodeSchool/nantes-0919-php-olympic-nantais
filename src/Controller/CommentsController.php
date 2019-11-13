<?php

namespace App\Controller;

use App\Model\CommentsManager;

class CommentsController extends AbstractController
{
    public function list(): string
    {
        $commentsManager = new CommentsManager();
        $comments = $commentsManager->selectAll();

        return $this->twig->render('Admin/Comments/list.html.twig', [
            'comments' => $comments,
        ]);
    }

    public function display(int $id): string
    {
        $commentsManager = new CommentsManager();
        $comments = $commentsManager->selectOneById($id);

        return $this->twig->render('Admin/Comments/display.html.twig', [
            'comments' => $comments,
        ]);
    }

    public function send(): string
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isValid = true;
            $userLastNameError = null;
            $userFirstNameError = null;
            $userEmailError = null;
            $userSubjectError = null;
            $userMessageError = null;

            if (!isset($_POST['last_name']) || empty($_POST['last_name'])) {
                $userLastNameError = "Merci de renseigner votre nom";
                $isValid = false;
            }

            if (!isset($_POST['first_name']) || empty($_POST['first_name'])) {
                $userFirstNameError = "Merci de renseigner votre prénom";
                $isValid = false;
            }

            if (!isset($_POST['user_email']) || empty($_POST['user_email'])) {
                $userEmailError = "Merci d'entrer votre email";
                $isValid = false;
            } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                $userEmailError = "Merci de renseigner un email valide";
                $isValid = false;
            }

            if (!isset($_POST['subject']) || empty($_POST['subject'])) {
                $userSubjectError = "Merci de préciser l'objet de votre message";
                $isValid = false;
            }

            if (!isset($_POST['user_message']) || empty($_POST['user_message'])) {
                $userMessageError = "Merci d'entrer votre message";
                $isValid = false;
            }

            if ($isValid) {
                $commentsManager = new CommentsManager();
                if ($commentsManager->insertComments()) {
                    header("Location:/olympic/contact");
                }
            }

            return $this->twig->render("Olympic/contact.html.twig", [
                "lastNameError" => $userLastNameError,
                "firstNameError" => $userFirstNameError,
                "userEmailError" => $userEmailError,
                "subjectError" => $userSubjectError,
                "userMessageError" => $userMessageError,
            ]);
        }
    }
}
