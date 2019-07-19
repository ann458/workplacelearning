<?php

namespace App\Http\Controllers;

use App\LearningActivityActing;
use App\Repository\Eloquent\LearningActivityActingRepository;
use App\Services\ActingActivityExporter;
use App\Services\CurrentUserResolver;
use App\Traits\PhpWordDownloader;
use Illuminate\Http\Request;

class ActingActivityExport extends Controller
{
    use PhpWordDownloader;
    /**
     * @var LearningActivityActingRepository
     */
    private $actingRepository;
    /**
     * @var ActingActivityExporter
     */
    private $actingActivityExporter;

    public function __construct(LearningActivityActingRepository $actingRepository, ActingActivityExporter $actingActivityExporter)
    {
        $this->actingRepository = $actingRepository;
        $this->actingActivityExporter = $actingActivityExporter;
    }

    public function __invoke(Request $request, CurrentUserResolver $userResolver)
    {

        $activities = $this->actingRepository->getMultipleForUser($userResolver->getCurrentUser(), $request->get('ids'));

        $document = $this->actingActivityExporter->export($activities);

        $this->downloadDocument($document, 'activities.docx');
    }
}
