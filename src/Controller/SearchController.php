<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\Venue;

use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    /**
     * @var int
     */
    public $page=1;

/**
 * @var string
 */
public $q ='';

    /**
     * @var string
     */
    public $typeJeu = '';

    /**
     * @var null|integer
     */
    public $max ;

    /**
     * @var null|integer
     */
    public $min ;






}