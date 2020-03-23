<?php
namespace App\Model;

use App\Database\DBConnection;

class PollModel
{

    /**
     * Database connection.
     */
    private $connection;
    
    /**
     * pollTable
     *
     * @var string
     */
    private $pollTable = 'poll';    
    /**
     * pollOptionTable
     *
     * @var string
     */
    private $pollOptionTable = 'poll_option';    
    /**
     * pollUserTable
     *
     * @var string
     */
    private $pollUserTable = 'poll_user';
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $instance =  DBConnection::getInstance();
        $this->connection = $instance->getConnection();
    }
  
    /**
     * update
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {
        $poll = [
            "id" => $id,
            "expiration_date" => $data['poll_expiration_date'],
            "question" => $data['poll_ques'],
        ];
        try {
            $this->connection->beginTransaction();

            //Update Poll Data
            $stmt = $this->connection->prepare("UPDATE $this->pollTable SET question = :question, expiration_date =:expiration_date WHERE id=:id ");
            $stmt->execute($poll);

            //Drop all the options
            $param = [
                "poll_id" => $id,
            ];
            $stmt = $this->connection->prepare("DELETE FROM $this->pollOptionTable WHERE poll_id = :poll_id");
            $stmt->execute($param);

            //insert again all the option with updated data
            $pollData = $this->preparePollOption($data, $id);
            for ($i = 0; $i < count($data['answer']); $i++) {
                $values[] = "(:answer$i,:color_code$i,:vote_counter$i,:display_order$i,:poll_id$i)";
            }
            $values = implode(",", $values);
            $optionQuery = "INSERT INTO $this->pollOptionTable (answer, color_code, vote_counter, display_order, poll_id) VALUES $values";
            $stmt = $this->connection->prepare($optionQuery);
            //Bind our values.
            foreach ($pollData as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }
    
    /**
     * insert
     *
     * @param  mixed $data
     * @return void
     */
    public function insert($data)
    {
        $poll = [
            "question" => $data['poll_ques'],
            "expiration_date" => $data['poll_expiration_date']
        ];
        $stmt = $this->connection->prepare("INSERT INTO $this->pollTable (question, expiration_date) VALUES (:question,:expiration_date)");
        try {
            $this->connection->beginTransaction();
            $stmt->execute($poll);

            $pollData = $this->preparePollOption($data, $this->connection->lastInsertId());
            for ($i = 0; $i < count($data['answer']); $i++) {
                $values[] = "(:answer$i,:color_code$i,:vote_counter$i,:display_order$i,:poll_id$i)";
            }
            $values = implode(",", $values);
            $optionQuery = "INSERT INTO $this->pollOptionTable (answer, color_code, vote_counter, display_order, poll_id) VALUES $values";
            $stmt = $this->connection->prepare($optionQuery);
            //Bind our values.
            foreach ($pollData as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }
    
        
    /**
     * getPoll
     *
     * @param  mixed $id
     * @return void
     */
    public function getPoll($id=0)
    {
        $param = [];
        $sql = "SELECT * from $this->pollTable";
        if ($id && is_numeric($id)) {
            $sql .= " WHERE id = :id";
            $param = array(':id' => $id);
        }
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($param);
            $result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $result = $stmt->fetchAll();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }

    
    /**
     * getPollOptions
     *
     * @param  mixed $id
     * @return void
     */
    public function getPollOptions($id=0)
    {
        $param = [];
        $sql = "SELECT p.id,p.question,p.expiration_date,a.answer,a.id as option_id, a.vote_counter,a.display_order,a.color_code from $this->pollTable as p 
        INNER JOIN $this->pollOptionTable as a on p.id = a.poll_id ";
        if ($id && is_numeric($id)) {
            $sql .= " WHERE p.id = :id ";
            $param = array(':id' => $id);
        }
        $sql .= " order by a.display_order";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($param);
            $result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $result = $stmt->fetchAll();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }
    
    /**
     * getTodaysPoll
     *
     * @return void
     */
    public function getTodaysPoll()
    {
        $sql = "SELECT p.id,p.question,p.expiration_date from $this->pollTable as p WHERE DATE_FORMAT(p.created,'%Y-%m-%d') =  '".date("Y-m-d")."' order by id desc limit 1";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $result = $stmt->fetchAll();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }

    
    /**
     * saveVote
     *
     * @param  mixed $data
     * @return void
     */
    public function saveVote($data)
    {
        $param = [
            "id" => $data['option_id'],
        ];
        $stmt = $this->connection->prepare("UPDATE $this->pollOptionTable SET vote_counter = vote_counter+1 WHERE id = :id");
        try {
            $this->connection->beginTransaction();
            $stmt->execute($param);

            $param = [
                "poll_id" => $data['id'],
                "user_cookie" => $data['user_cookie']
            ];
            $stmt = $this->connection->prepare("INSERT INTO $this->pollUserTable (user_cookie, poll_id) VALUES (:user_cookie,:poll_id)");
            $stmt->execute($param);
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }

    
    /**
     * checkVote
     *
     * @param  mixed $data
     * @return void
     */
    public function checkVote($data)
    {
        $param = [
            "poll_id" => $data['id'],
            "user_cookie" => $data['user_cookie']
        ];
        $stmt = $this->connection->prepare("SELECT * FROM $this->pollUserTable WHERE poll_id = :poll_id and user_cookie = :user_cookie");
        try {
            $stmt->execute($param);
            $result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            return $result = $stmt->fetchAll();
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }
    
    /**
     * preparePollOption
     *
     * @param  mixed $data
     * @param  mixed $pollId
     * @return void
     */
    private function preparePollOption($data, $pollId)
    {
        $pollAnswer = [];
        foreach ($data['answer'] as $key => $value) {
            $pollAnswer["answer$key"] = $value;
            $pollAnswer["color_code$key"] = $data['answer_color'][$key];
            $pollAnswer["vote_counter$key"] =  $data['answer_counter'][$key];
            $pollAnswer["display_order$key"] = $data['answer_order'][$key];
            $pollAnswer["poll_id$key"] = $pollId;
        }
        return $pollAnswer;
    }

    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        $param = [];
        $sql = "DELETE p from $this->pollTable as p WHERE p.id=:id";
        $param = array(':id' => $id);
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($param);
        } catch (Exception $e) {
            $this->connection->rollback();
            echo $e->getMessage();
            die;
            throw $e;
        }
    }
}
