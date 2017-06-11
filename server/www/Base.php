<?php
class Base
{
    private $base;

    public function __construct()
    {
        $this->base = new PDO("mysql:host=" . Config::$host . ";dbname=" . Config::$db_name, Config::$db_user, Config::$db_password);
        $this->base->query("set names utf8");
    }

    public function addEmail($email)
    {
        $query = "INSERT INTO emails (uuid, email, deleted) VALUES (:uuid, :email, FALSE)";
        $sql = $this->base->prepare($query);
        $sql->bindParam(':uuid', uniqid());
        $sql->bindParam(':email', $email);
        $sql->execute();
    }

    public function updateEmail($newEmail, $oldEmail)
    {
        $query = "
		UPDATE emails
		SET
			email = :new_email
		WHERE 
			email = :old_email";
        $sql = $this->base->prepare($query);
        $sql->bindParam(':new_email', $newEmail);
        $sql->bindParam(':old_email', $oldEmail);
        $sql->execute();
    }

    public function enableEmail($email)
    {
        $query = "
		UPDATE emails
		SET
			deleted = FALSE
		WHERE 
			email = :email";
        $sql = $this->base->prepare($query);
        $sql->bindParam(':email', $email);
        $sql->execute();
    }

    public function disableEmail($email)
    {
        $query = "
		UPDATE emails
		SET
			deleted = TRUE
		WHERE 
			email = :email";
        $sql = $this->base->prepare($query);
        $sql->bindParam(':email', $email);
        $sql->execute();
    }

    public function disableEmailByUuid($uuid)
    {
        $query = "
		UPDATE emails
		SET
			deleted = TRUE
		WHERE 
			uuid = :uuid";
        $sql = $this->base->prepare($query);
        $sql->bindParam(':uuid', $uuid);
        $sql->execute();
    }

    public function emailExists($email)
    {
        $query = "SELECT COUNT(email) AS count FROM  emails WHERE email = :email";
        $sql = $this->base->prepare($query);
        $sql->bindParam(':email', $email);
        $sql->execute();
        $result = $sql->fetchObject();
        return $result->count > 0;
    }

    public function getActiveEmails()
    {
        $query = "SELECT email FROM emails WHERE deleted = FALSE";
        $sql = $this->base->prepare($query);
        $sql->execute($query);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fillNotificationTable()
    {
        $query = "
        INSERT INTO 
          reminders (email_uuid) 
        SELECT uuid FROM emails WHERE deleted = FALSE";
        $sql = $this->base->prepare($query);
        $sql->execute();
    }
}