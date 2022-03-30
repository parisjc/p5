<?php

namespace App\Controller;

use Lib\Abstracts\AbstractController;

class ContactController extends AbstractController
{
    public function DefaultAction()
    {
        $user = (isset($_SESSION['user']))?$_SESSION['user']:"";
        echo $this->render('home/contact.twig',array('user'=>$user));
    }

    public function ContactEmail($nom,$email,$object,$message)
    {
        $to = "jckparis38@gmail.com";
        $from = "contact@jc-paris.fr"; // adresse MAIL OVH liée l'hébergement.

        // *** Laisser tel quel

        $JOUR = date("Y-m-d");
        $HEURE = date("H:i");

        $Subject = "Mail Formulaire Contact - $JOUR $HEURE";

        $mail_Data = "";
        $mail_Data .= " \n";
        $mail_Data .= " \n";
        $mail_Data .= " \n";
        $mail_Data .= " \n";
        $mail_Data .= "$nom \n";

        $mail_Data .= "Object : $object , Email : $email
        \n";
        $mail_Data .= "
        \n";
        $mail_Data .= "$message
        \n";
        $mail_Data .= " \n";
        $mail_Data .= " \n";

        $headers = "MIME-Version: 1.0 \n";
        $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
        $headers .= "From: $from \n";
        $headers .= "Disposition-Notification-To: $from \n";

        // Message de Priorité haute
        // -------------------------
        $headers .= "X-Priority: 1 \n";
        $headers .= "X-MSMail-Priority: High \n";

        $CR_Mail = TRUE;

        $CR_Mail = @mail ($to, $Subject, $mail_Data, $headers);

        if ($CR_Mail === FALSE) echo false;
        else echo true;
    }
}