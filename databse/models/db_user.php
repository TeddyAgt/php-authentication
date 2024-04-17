<?php

class UserAccess
{
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementReadOneByUserName;

    function __construct(private PDO $pdo)
    {
        $this->statementCreateOne = $pdo->prepare('INSERT INTO user VALUES (DEFAULT, :user_name, :email, :password, :presentation, DEFAULT)');
        $this->statementReadOneByUserName = $pdo->prepare('SELECT * FROM user WHERE user_name=:user_name');
    }

    function createUser(array $user): void
    {
        $hashedPassword = password_hash($user['password'], PASSWORD_ARGON2I);
        $this->statementCreateOne->bindValue(':user_name', $user['username']);
        $this->statementCreateOne->bindValue(':email', $user['email']);
        $this->statementCreateOne->bindValue(':password', $hashedPassword);
        $this->statementCreateOne->bindValue(':presentation', $user['presentation']);
        $this->statementCreateOne->execute();
        return;
    }

    function getUserByUserName(string $userName)
    {
        $this->statementReadOneByUserName->bindValue(':user_name', $userName);
        $this->statementReadOneByUserName->execute();
        return $this->statementReadOneByUserName->fetch();
    }
}

return new UserAccess($pdo);
