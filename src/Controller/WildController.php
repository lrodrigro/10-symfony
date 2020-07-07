<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/", name="wild_index")
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        foreach($programs as $program)
        {
            //echo $program->getTitle();
            $program->url = preg_replace(
                '/ /',
                '-', mb_strtolower(trim(strip_tags($program->getTitle()), "-")));
        }
        //var_dump($programs);

        //var_dump($programs);
        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }


    /**
     * @Route("/wild/{id}", name="wild_show")
     */
    public function show(Program $program): Response
    {
        return $this->render('wild/program.html.twig', ['program' => $program]);
    }

    /**
     * @Route("/episode/{id}", name="episode_show")
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }
}

