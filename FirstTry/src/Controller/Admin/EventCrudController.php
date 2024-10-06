<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Enum\EventType;
use App\Enum\RecurrenceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Twig\Node\Expression\Test\EvenTest;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('userOrganisator',"UserOrganisator")
                ->setCrudController(UserCrudController::class),
            TextField::new('title'),
            DateTimeField::new('dateStart'),
            DateTimeField::new('dateEnd'),
            ChoiceField::new("recurrenceType")
                ->setChoices([
                    "Daily" => RecurrenceType::DAILY,
                    "Weekly" => RecurrenceType::WEEKLY,
                    "Monthly" => RecurrenceType::MONTHLY,
                    "Yearly" => RecurrenceType::YEARLY,
                    "None" => RecurrenceType::NONE,
                ]),
            DateTimeField::new("recurrenceEnd"),
            TextEditorField::new('description'),
            MoneyField::new("fee")->setCurrency('EUR'),
            ChoiceField::new("eventType")
                ->setChoices([
                    "Boardgames_Demo" => EventType::BOARDGAMES_DEMO,
                    "Role_Play" => EventType::ROLE_PLAY,
                    "Festival" => EventType::FESTIVAL,
                    "Gaming_Sales" => EventType::GAMING_SALES,
                    "Tournament" => EventType::TOURNAMENT,
                ])
            
        ];
    }
}
