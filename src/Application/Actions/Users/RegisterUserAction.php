<?php
declare(strict_types=1);

namespace App\Application\Actions\Users;

use App\Application\Actions\AbstractAction;
use App\Domain\Common\Values\{Email, PersonalName};
use App\Domain\Users\{User, UserRepository};
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface as Logger;

class RegisterUserAction extends AbstractAction {
    private UserRepository $repo;

    public function __construct(UserRepository $repo, Logger $logger) {
        $this->repo = $repo;
        parent::__construct($logger);
    }

    public function perform(): Response {
        $data = $this->getFormData();

        $email = Email::parse($data->email);
        $name = PersonalName::create($data->first_name, $data->last_name);
        $user = User::createNew($email, $name);

        $this->repo->store($user);

        return $this->respondWithData(['id' => $user->id]);
    }
}