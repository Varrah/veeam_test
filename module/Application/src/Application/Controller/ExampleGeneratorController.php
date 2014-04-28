<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
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
        foreach ($titles as $divisionTitle) {
            $division = new Division();
            $division->setTitle($divisionTitle);
            $objectManager->persist($division);

            $vacancy = new Vacancy();
            $vacancy->setDivision($division);

            $objectManager->persist($vacancy);

            $vacancyText = new VacancyText();
            $vacancyText->setLanguage('en');
            $vacancyText->setTitleText($divisionTitle . ' title');
            $vacancyText->setDescriptionText($divisionTitle . ' description');
            $vacancyText->setVacancy($vacancy);

            $objectManager->persist($vacancyText);
        }

        $objectManager->flush();

        return new ViewModel();
    }
}
