<?php

namespace App\Controller;

use App\Entity\Hit;
use App\Repository\HitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;

class HashController extends AbstractController
{
    /**
     * @Route("/generate/{inputted}/{requests}", name="generate")
     */
    
    public function generate($inputted, $requests, Request $request, RateLimiterFactory $anonymousApiLimiter)
    {
        $limiter = $anonymousApiLimiter->create($request->getClientIp());
        if (false === $limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
        $now = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        for ($count = 1; $count <= $requests; $count++){
            $i = 0;
            do {
                $key = substr(md5(Rand()), 0, 8);
                $hash = md5($key.$inputted);
                $i++;
            } while (substr_compare($hash, '0000', 0, 4));
            $hit = new Hit();
            $hit->setBatch($now);
            $hit->setAttempts($i);
            $hit->setBloc($count);
            $hit->setHash($hash);
            $hit->setInput($inputted);
            $hit->setCorrectKey($key);

            $doctrine = $this->getDoctrine()->getManager();
            $doctrine->persist($hit);
            $doctrine->flush();
            $inputted = $hash;
        }
        return $this->json([
            'key' => $inputted
        ]);
    }

    /**
     * @Route("/hits", name="hits")
     */

    public function index(Request $request, HitRepository $hitRepository, PaginatorInterface $paginator)
    {
        $perPage = 10;
        $page = $request->query->getInt('page', 1);
        $filter = $request->query->get('filter', null);
        $hits = $hitRepository->findByMaxAttempts($filter);
        $data = [];
        $data['Página Atual'] = $page;
        $data['Total de Páginas'] = round(count($hits)/$perPage);
        $hits = $paginator->paginate($hits, $page, $perPage);
        foreach ($hits as $hit) {
            $data[] = [
                'Batch' => $hit->getBatch()->format('d/m/Y H:i:s'),
                'Bloco' => $hit->getBloc(),
                'Entrada' => $hit->getInput(),
                'Chave Correta' => $hit->getCorrectKey(),
            ];
        }

        return $this->json($data);
    }
}
