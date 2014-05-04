<?php
/**
 * Small generator script, just to make DB filling automated
 * since add/remove/edit actions were not required by the original specification
 */

namespace Application\Controller;

use Application\Entity\Division;
use Application\Entity\Vacancy;
use Application\Entity\VacancyText;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ExampleGeneratorController extends AbstractActionController
{
    public function generateAction()
    {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $titles = array('PHP Dev', 'QA', 'HR');
        $languages = array( 'ru', 'en', 'it', 'fr', 'es', 'cn', 'jp', 'de', 'ua' );

        foreach ($titles as $divisionTitle) {
            $division = new Division();
            $division->setTitle($divisionTitle . rand( 0, 1000 ));
            $objectManager->persist($division);

            $vacancy = new Vacancy();
            $vacancy->setDivision($division);

            $objectManager->persist($vacancy);

            $vacancyText = new VacancyText();
            $vacancyText->setLanguage($languages[rand(0,10)]);
            $vacancyText->setTitleText($divisionTitle . ' title' . rand( 0, 1000 ));
            $vacancyText->setDescriptionText($divisionTitle . ' description' . rand( 0, 1000 ));
            $vacancyText->setVacancy($vacancy);

            $objectManager->persist($vacancyText);
        }

        $objectManager->flush();

        return new ViewModel();
    }
}
