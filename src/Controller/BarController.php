<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BarController extends AbstractController
{

  public function __construct(HttpClientInterface $client)
  {
    $this->client = $client;
  }

  private function beers_api(): Array
  {
    $response = $this->client->request(
      'GET',
      'https://raw.githubusercontent.com/Antoine07/hetic_symfony/main/Introduction/Data/beers.json'
    );

    $statusCode = $response->getStatusCode();
    // $statusCode = 200
    $contentType = $response->getHeaders()['content-type'][0];
    // $contentType = 'application/json'
    $content = $response->getContent();
    // $content = '{"id":521583, "name":"symfony-docs", ...}'
    $content = $response->toArray();
    // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

    return $content;
  }

  /**
   * @Route("/bar", name="bar")
   */
  public function index(): Response
  {
    return $this->render('bar/index.html.twig', [
      'controller_name' => 'BarController',
    ]);
  }

  /**
   * @Route("/mentions", name="mentions")
   */
  public function mention(): Response
  {
    
    return $this->render('mentions/index.html.twig', [
      'title' => 'Page Mention',
      'beers' => $this->beers_api()
    ]);
  }

  /**
   * @Route("/beers", name="beers")
   */
  public function beers()
  {
    // dump($this->beers_api());
    return $this->render('beers/index.html.twig', [
      'title' => 'The Beer',
      'beers' => $this->beers_api()["beers"]
    ]);
  }

  /**
   * @Route("/home", name="home")
   */
  public function home()
  {
    // dump($this->beers_api());
    return $this->render('home/index.html.twig', [
      'title' => 'Home Page',
    ]);
  }
}