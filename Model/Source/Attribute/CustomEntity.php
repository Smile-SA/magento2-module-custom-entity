<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\Source\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;

/**
 * CustomEntity options source model.
 */
class CustomEntity extends AbstractSource
{
    protected CustomEntityRepositoryInterface $customEntityRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        CustomEntityRepositoryInterface $customEntityRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customEntityRepository = $customEntityRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getAllOptions(): array
    {
        $customEntityTypeId = $this->getAttribute()->getData('custom_entity_attribute_set_id');
        if (!$customEntityTypeId) {
            return [];
        }

        if ($this->_options === null) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter(
                'attribute_set_id',
                $customEntityTypeId
            )->create();
            $entities = $this->customEntityRepository->getList($searchCriteria);
            foreach ($entities->getItems() as $entity) {
                $this->_options[] = ['label' => $entity->getName(), 'value' => $entity->getId()];
            }
        }

        return $this->_options;
    }
}
