<?php

namespace app\components\attachedFiles;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

final class AttachFileQuery extends ActiveQuery
{
    public function byModel(string $modelName): self
    {
        return $this->andWhere(['modelName' => $modelName]);
    }

    public function byKey(?string $modelKey): self
    {
        return $modelKey? $this->andWhere(['modelKey' => $modelKey]) : $this;
    }

    public function byHash(string|null $hash = null): self
    {
        if ($hash){
            return $this->andWhere(['file_hash' => $hash]);
        }

        return $this;
    }

    public function byType(string|null $type = null): self
    {
        if ($type){
            return $this->andWhere(['file_type' => $type]);
        }

        return $this;
    }

    public function byStatus(string|null $status = null): self
    {
        if ($status){
            return $this->andWhere(['file_status' => $status]);
        }

        return $this;
    }

    public function byTags(
        string|array|null $tags = null,
        string|null $tagsCondition = null
    ): self {
        if ($tags){
            if (is_string($tags)){
                return $this->andWhere(['like', 'file_tags', $tags]);
            }

            $condition = [$tagsCondition ?: 'or'];
            foreach ($tags as $tag) {
                $condition[] = ['like', 'file_tags', $tag];
            }

            return $this->andWhere($condition);
        }

        return $this;
    }

    public function lastVersion(
        string $name,
        string $key,
        string $type
    ): self {
        return $this->where([
            'modelName' => $name,
            'modelKey' => $key,
            'file_type' => $type
        ]);
    }

    public function all($db = null): array|ActiveRecord
    {
        return parent::all($db);
    }

    public function one($db = null): ActiveRecord|array|null
    {
        $this->limit(1);
        return parent::one($db);
    }
}
