<?php
namespace McCaulay\Trustpilot\API\BusinessUnit\Review\Product;

use Illuminate\Support\Collection;
use McCaulay\Trustpilot\API\BusinessUnit\Review;
use McCaulay\Trustpilot\API\ResourceApi;

class ImportedProductReviewApi extends ResourceApi
{
    /**
     * The business unit id.
     *
     * @var int
     */
    public $businessUnitId = null;

    /**
     * Initialise the business unit review invitations with an optional business unit id.
     * If no business unit id is given, it uses the business unit from the config.
     *
     * @param null|string $businessUnitId
     */
    public function __construct(?string $businessUnitId = null)
    {
        parent::__construct();
        $this->businessUnitId = $businessUnitId ?? config('trustpilot.unit_id');
        $this->setPath('/private/business-units/' . $this->businessUnitId);
    }

    /**
     * Perform the query and get the results.
     *
     * @param array $query
     * @param bool $search
     * @return \Illuminate\Support\Collection;
     */
    public function perform(array $query, bool $search = false): Collection
    {
        $response = $this->get('/email-invitations', $query);
        return collect($response->productReviews)->map(function ($productReview) {
            return (new ProductReview())->data($productReview);
        });
    }
}
