<?php


namespace App\Http\Controllers\API\Tracking;


use App\Http\Controllers\API\Tracking\Traits\Clients;
use App\Http\Controllers\API\Tracking\Traits\Products;
use App\Http\Controllers\API\Tracking\Traits\Team;
use App\Http\Controllers\API\Tracking\Traits\Tickets;
use App\Http\Controllers\Controller;
use App\Repositories\TeamRepository;
use App\Repositories\TicketRepository;
use App\Repositories\TrackingReportRepository;
use App\Repositories\TrackingRepository;
use App\Repositories\TrackingProjectRepository;

class BaseController extends Controller
{
    use Clients, Products, Team, Tickets;

    protected $trackingRepo;
    protected $trackingProjectsRepo;
    protected $teamRepo;
    protected $trackingReportRepo;
    protected $ticketRepo;

    public function __construct(
        TrackingRepository $trackingRepository,
        TrackingProjectRepository $trackingProjectRepository,
        TeamRepository $teamRepository,
        TrackingReportRepository $trackingReportRepository,
        TicketRepository $ticketRepository
    )
    {
        $this->trackingRepo = $trackingRepository;
        $this->trackingProjectsRepo = $trackingProjectRepository;
        $this->teamRepo = $teamRepository;
        $this->trackingReportRepo = $trackingReportRepository;
        $this->ticketRepo = $ticketRepository;
    }
}
