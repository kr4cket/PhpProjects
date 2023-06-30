<?php

namespace App\Models;

use \App\Core\Model;

class GoodsReviewModel extends Model
{
    private $pageLimit = 3;
    private $paramRules = [
        'name' => ['isEmpty', 'minLength', 'isLetter'],
        'surname' => ['isEmpty', 'minLength', 'isLetter'],
        'phoneNumber' => ['isPhoneNumber', 'isEmpty'],
        'rating' => ['isChecked']
    ];

    public function getFormData($postData=[]): array
    {
        if (!$postData) {
            return [
                'name' => '',
                'surname' => '',
                'phoneNumber' => '',
                'review' => '',
                'rating' => -1,
                'errors' => []
            ];
        }
        return [
            'id' => $postData['id'] ?? '',
            'name' => $postData['name'] ?? '',
            'surname' => $postData['surname'] ?? '',
            'phoneNumber' => $postData['phoneNumber'] ?? '',
            'review' => $postData['review'] ?? '',
            'rating' => $this->getRadio($postData),
            'errors' => $this->validator->getErrors()
        ];
    }

    public function isValid($validateData): bool
    {
        $validateData['rating'] = $this->getRadio($validateData);
        foreach ($validateData as $type => $param) {
            
            if (array_key_exists($type, $this->paramRules)) {
                $this->validator->validate($this->paramRules[$type], $param);
            }
        }

        return empty($this->validator->getErrors());
    }

    public function getReviews($productId): array
    {
        $reviewData = $this->model->prepare("SELECT * FROM goods_review WHERE goods_id=:id");
        $reviewData->execute(['id'=>$productId]);
        return $reviewData->fetchAll();
    }

    public function getAllReviews(): int
    {
        $count = $this->model->query("SELECT COUNT(*) FROM goods_review");
        return $count->fetch()['COUNT(*)'];
    }

    public function getModeratedReviews(): int
    {
        $count = $this->model->query("SELECT COUNT(*) FROM goods_review WHERE is_active=0");
        return $count->fetch()['COUNT(*)'];
    }

    public function getActiveReviews(): int
    {
        $count = $this->model->query("SELECT COUNT(*) FROM goods_review WHERE is_active=1");
        return $count->fetch()['COUNT(*)'];
    }

    public function addReview($data) 
    {
        $goodData = $this->model->prepare("INSERT INTO goods_review (id, goods_id, name, surname, phone_number,
        is_active, review, rating) VALUES (:id, :goods_id ,:name, :surname, :phone_number, :is_active, :review, :rating);");
        $goodData->execute([
            'id' => null,
            'goods_id' => $data['good_id'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone_number' => $data['phoneNumber'],
            'review' => $data['review'],
            'rating' => $data['rating'],
            'is_active' => $data['is_active'] ?? 1
        ]);
    }
    
    public function getInactiveReviews()
    {
        $request = $this->model->query("SELECT * FROM goods_review WHERE is_active=0");
        $request->execute();
        return $request->fetchAll();
    }

    public function getReviewPage($page) 
    {
        $startPage = ($page-1)*$this->pageLimit;
        $request = $this->model->prepare("SELECT * FROM goods_review WHERE is_active=0 LIMIT :page, :limit");
        $request->execute(['page' => $startPage, 'limit' => $this->pageLimit]);
        return $request->fetchAll();
    }

    public function getReviewCount() 
    {
        $request = $this->model->prepare("SELECT COUNT(*) FROM goods_review WHERE is_active=0");
        $request->execute();
        return ceil($request->fetch()['COUNT(*)']/$this->pageLimit);
    }

    public function deleteReview($id) 
    {
        $request = $this->model->prepare("DELETE FROM goods_review WHERE id=:id");
        $request->execute(["id"=>$id]);
    }

    public function allowReview($id) 
    {
        $request = $this->model->prepare("UPDATE goods_review SET is_active = 1 WHERE id=:id");
        $request->execute(["id"=>$id]);
    }

    private function getRadio($data): int
    {
        if (isset($data['rating'])) {
            return $data['rating'];
        }

        return 0;
    }

}
