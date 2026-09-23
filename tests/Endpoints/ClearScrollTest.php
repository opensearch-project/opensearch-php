<?php

namespace OpenSearch\Tests\Endpoints;

use OpenSearch\Endpoints\ClearScroll;
use PHPUnit\Framework\TestCase;

class ClearScrollTest extends TestCase {

  private ClearScroll $instance;

  /**
   * @inheritDoc
   */
  protected function setUp(): void
  {
    $this->instance = new ClearScroll();
  }

  public function testEncodingOfScrollId(): void
  {
    $this->instance->setScrollId('FGluY2x1ZGVfY29udGV4dF91dWlkDXF1ZXJ5QW5kRmV0Y2gBFkFpanFTbnpHUWFLVTBBTVhCT2lqNHcAAAAAAAAAGRZEaExWNDlOalRiS1FXX1hVQVdWM0ZnAQEZY2FzZWZvbGRlcnMtY291cnRhcGktdGVzdA==');

    $result = $this->instance->getURI();

    // Assert
    $this->assertEquals('/_search/scroll/FGluY2x1ZGVfY29udGV4dF91dWlkDXF1ZXJ5QW5kRmV0Y2gBFkFpanFTbnpHUWFLVTBBTVhCT2lqNHcAAAAAAAAAGRZEaExWNDlOalRiS1FXX1hVQVdWM0ZnAQEZY2FzZWZvbGRlcnMtY291cnRhcGktdGVzdA%3D%3D', $result);
  }

}
