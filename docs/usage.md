# Usage
## Initiate SLS
To use `SLS`, simply include the SLS.php file and create a new instance of the `SLS` class.

```php
<?php

// Import additionnal class into the global namespace
use LaswitchTech\coreSLS\SLS;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Initiate SLS
$SLS = new SLS();
```

### Properties
`SLS` provides the following properties:

#### core Modules
- [Configurator](https://github.com/LaswitchTech/coreConfigurator)
- [Database](https://github.com/LaswitchTech/coreDatabase)
- [Logger](https://github.com/LaswitchTech/coreLogger)

### Controller
coreSLS includes a controller class that can be extended to create your own controllers. The controller class provides methods to handle the request and response of your API.

See [coreBase](https://github.com/LaswitchTech/coreBase) for more information.

#### Example
```php

// Import Controller class into the global namespace
use LaswitchTech\coreSLS\Controller;

class LspController extends Controller {}
```

### Command
coreSLS includes a command class that can be extended to create your own commands. The command class provides methods to handle the request and response of your CLI.

See [coreBase](https://github.com/LaswitchTech/coreBase) for more information.

#### Example
```php

// Import Command class into the global namespace
use LaswitchTech\coreSLS\Command;

class LspCommand extends Command {}
```
