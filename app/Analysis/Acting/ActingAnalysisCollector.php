<?php

namespace App\Analysis\Acting;

use App\Competence;
use App\LearningGoal;
use App\ResourceMaterial;
use App\ResourcePerson;
use App\Timeslot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class ActingAnalysisCollector fetches data for the analysis, caches it in object.
 */
class ActingAnalysisCollector
{
    private $learningActivities;
    private $timeslots;
    private $learningGoals;
    private $competencies;
    private $resourcePersons;
    private $resourceMaterials;
    public $year;
    public $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * Limit a Collection from a certain date and +1 month.
     *
     * @param $collection
     * @param $year
     * @param $month
     */
    public function limitCollectionByDate(Collection $collection, $year, $month)
    {
        if ($year != 'all' && $month != 'all') {
            $dtime = mktime(0, 0, 0, intval($month), 1, intval($year));
            $collection = $collection->filter(function ($activity) use ($dtime) {
                $activityDate = strtotime($activity->date);
                $x = $activityDate >= $dtime && $activityDate <= strtotime('+1 month', $dtime);

                return $x;
            });
        }

        return $collection;
    }

    /**
     * Get all learning activities of the user.
     *
     * @return Collection
     */
    public function getLearningActivities()
    {
        if ($this->learningActivities === null) {
            $this->learningActivities = $this->limitCollectionByDate(Auth::user()->getCurrentWorkplaceLearningPeriod()->learningActivityActing()->with(['competence', 'timeslot', 'learningGoal', 'resourcePerson'])->get(), $this->year, $this->month);
        }

        return $this->learningActivities;
    }

    /**
     * Get all the timeslots of the user's education program.
     *
     * @return Collection|Timeslot[]
     */
    public function getTimeslots()
    {
        if ($this->timeslots === null) {
            $this->timeslots = Auth::user()->currentCohort()->timeslots()->get()->merge(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getTimeslots()
            );
        }

        return $this->timeslots;
    }

    /**
     * Get all the learning goals for the user's interning place.
     *
     * @return Collection|LearningGoal[]
     */
    public function getLearningGoals()
    {
        if ($this->learningGoals === null) {
            $this->learningGoals = Auth::user()->getCurrentWorkplaceLearningPeriod()->learningGoals;
        }

        return $this->learningGoals;
    }

    /**
     * Get all the competencies of the user's education program.
     *
     * @return Collection|Competence[]
     */
    public function getCompetencies()
    {
        if ($this->competencies === null) {
            $this->competencies = Auth::user()->currentCohort()->competencies()->get();
        }

        return $this->competencies;
    }

    /**
     * Get all the resource persons of the user's education program & internship.
     *
     * @return Collection|ResourcePerson[]
     */
    public function getResourcePersons()
    {
        if ($this->resourcePersons === null) {
            $this->resourcePersons = Auth::user()->currentCohort()->resourcePersons()->get()->merge(
                Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourcePersons()
            );
        }

        return $this->resourcePersons;
    }

    /**
     * Get all the resource materials of the user's internship.
     */
    public function getResourceMaterials()
    {
        if ($this->resourceMaterials === null) {
            $this->resourceMaterials = Auth::user()->getCurrentWorkplaceLearningPeriod()->getResourceMaterials();

            // "None" doesn't exist as a material, so stub it
            $noneMaterial = new ResourceMaterial();
            $noneMaterial->rm_id = null;
            $noneMaterial->rm_label = 'Geen';

            $this->resourceMaterials->add($noneMaterial);
        }

        return $this->resourceMaterials;
    }
}
