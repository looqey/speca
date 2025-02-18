<?php

namespace Looqey\Speca;

use Looqey\Speca\Contracts\Parseable;
use Looqey\Speca\Contracts\Serializable;
use Looqey\Speca\Traits\HasContexts;
use Looqey\Speca\Traits\Parses;
use Looqey\Speca\Traits\ManagesProperties;
use Looqey\Speca\Traits\Serializes;

class Data implements Serializable, Parseable, \JsonSerializable
{
    use HasContexts;
    use Serializes;
    use Parses;
    use ManagesProperties;
}
