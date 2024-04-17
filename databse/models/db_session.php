<?php

class SessionAccess
{
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementReadOneSession;
    private PDOStatement $statementReadOneUser;
    private PDOStatement $statementDeleteOne;

    function __construct(private PDO $pdo)
    {
        $this->statementCreateOne = $pdo->prepare('INSERT INTO session VALUES (:session_id, :user_id )');
        $this->statementReadOneSession = $pdo->prepare('SELECT * FROM session WHERE session_id=:session_id');
        $this->statementReadOneUser = $pdo->prepare('SELECT * FROM user WHERE user_id=:user_id');
        $this->statementDeleteOne = $pdo->prepare('DELETE FROM session WHERE session_id=:session_id');
    }

    function createSession(int $userId): void
    {
        $sessionId = bin2hex(random_bytes(32));
        $this->statementCreateOne->bindValue(":session_id", $sessionId);
        $this->statementCreateOne->bindValue(":user_id", $userId);
        $this->statementCreateOne->execute();
        $signature = hash_hmac('sha256', $sessionId, "C0sm0 |3 <h@t");
        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
        setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, '', '', false, true);
    }

    function isLoggedIn(): array | string
    {
        $sessionId = $_COOKIE['session'] ?? '';
        $signature = $_COOKIE['signature'] ?? '';

        if ($sessionId && $signature) {
            $hash = hash_hmac('sha256', $sessionId, "C0sm0 |3 <h@t");

            if (hash_equals($hash, $signature)) {
                $this->statementReadOneSession->bindValue(':session_id', $sessionId);
                $this->statementReadOneSession->execute();
                $session = $this->statementReadOneSession->fetch();
                if ($session) {
                    $this->statementReadOneUser->bindValue(':user_id', $session['user_id']);
                    $this->statementReadOneUser->execute();
                    $user = $this->statementReadOneUser->fetch();
                }
            }
        }
        return $user ?? '';
    }

    function logOut(): void
    {
        $sessionId = $_COOKIE['session'] ?? '';
        $this->statementDeleteOne->bindValue(':session_id', $sessionId);
        $this->statementDeleteOne->execute();
        setcookie('session', '', time() - 1);
        setcookie('signature', '', time() - 1);
        return;
    }
}

return new SessionAccess($pdo);
