<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Plateforme de reservation de ressources ",
 *     version="1.0.0",
 *     description=" Une startup souhaite disposer d’une plateforme web interne permettant à ses employés de réserver différents types de ressources (salles de réunion, postes de travail, vidéoprojecteurs, etc.). Le back-end sera développé en Laravel, avec une API REST sécurisée, et un front-end Blade pour la démonstration. Les données seront stockées dans MySQL."
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8080/api",
 *     description="J'utilise Docker qui est un outil qui permet de creer un des conteneurs pour qu'une application puisee tourne correctement . "
 * )
 */

class Controller extends BaseController 
{
    use AuthorizesRequests, DispatchesJobs,ValidatesRequests ;
}
