<?php

declare(strict_types=1);

namespace RunwayHub\Modules\Home\Controllers;

use RunwayHub\Core\Controller;
use RunwayHub\Core\Database;

class HomeController extends Controller
{
    /**
     * Index action - Show homepage
     */
    public function index(): \RunwayHub\Core\Response
    {
        $this->render('dashboard');

        return $this->getResponse();
    }
}
