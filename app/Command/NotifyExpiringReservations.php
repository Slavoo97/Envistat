<?php

namespace App\Command;

use App\Model\Entity\Reservation;
use App\Model\Services\EmployeeCarRepository;
use App\Model\Services\ReservationRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class NotifyExpiringReservations extends Command
{

    protected static $defaultName = 'app:notify:reservations';
    private ReservationRepository $reservationRepository;

    /**
     * Pass dependencies with constructor injection
     */
    public function __construct(EmployeeCarRepository $employeeCarRepository)
    {
        parent::__construct();
        $this->employeeCarRepository = $employeeCarRepository;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $reservations = $this->reservationRepository->findReservationsBy(['waiting' => false]);

        foreach ($reservations as $reservation){
            /** @var  Reservation $reservation */
            $dateTime1 = $reservation->getCreatedAt();
            $dateTime2 = new \DateTime();

            $interval = $dateTime1->diff($dateTime2);
            $minutesDifference = $interval->i;

            if ($minutesDifference < 30) {
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = 0;
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.m1.websupport.sk';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'slavo.svigar@swigsolutions.sk';
                    $mail->Password   = 'Bj90hQ:r|H';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;

                    //Recipients
                    $mail->setFrom('slavo.svigar@swigsolutions.sk', 'Telekom Parking system');
                    $mail->addAddress($reservation->getEmployee()->getEmail());

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Parkovacie miesto Vá vyprší za menej než 30 minút';
                    $mail->Body    = 'Parkovacie miesto Vá vyprší za menej než 30 minút';
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }

        return 0;
    }

}
