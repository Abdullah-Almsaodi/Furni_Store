<?php
// Database configuration
$host = "localhost";
$dbName = "furniture_store";
$username = "root";
$password = "";

try {
    // Create a new PDO instance
    $db = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);

    // Set PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set additional security attributes
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disable prepared statement emulation
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode to associative array
    $db->setAttribute(PDO::ATTR_PERSISTENT, false); // Disable persistent connections



} catch (PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
    die();
}

?>





<!-- In addition to the previous script, the updated version includes the following security attributes:

PDO::ATTR_EMULATE_PREPARES is set to false. This attribute disables the emulation of prepared statements, ensuring that the actual prepared statement functionality of the underlying database driver is used. This helps prevent SQL injection attacks by properly handling parameterized queries.

PDO::ATTR_DEFAULT_FETCH_MODE is set to PDO::FETCH_ASSOC. This attribute sets the default fetch mode to associative array mode. With this mode, the fetch() method will return an associative array with column names as keys, providing a more secure and convenient way to retrieve data from result sets.

PDO::ATTR_PERSISTENT is set to false. This attribute disables persistent connections, which ensures that each new request to the database will establish a fresh connection. Persistent connections can potentially lead to security vulnerabilities, so disabling them is recommended in most cases.

By including these additional security attributes, you enhance the security and reliability of your PDO-based database connection in the PHP script. -->


<!-- بالإضافة إلى البرنامج النصي السابق، يتضمن الإصدار المحدث سمات الأمان التالية:

تم ضبط PDO::ATTR_EMULATE_PREPARES على خطأ. تعمل هذه السمة على تعطيل محاكاة البيانات المعدة، مما يضمن استخدام وظيفة العبارة المعدة فعليًا لبرنامج تشغيل قاعدة البيانات الأساسية. يساعد هذا في منع هجمات حقن SQL عن طريق التعامل مع الاستعلامات ذات المعلمات بشكل صحيح.

تم ضبط PDO::ATTR_DEFAULT_FETCH_MODE على PDO::FETCH_ASSOC. تقوم هذه السمة بتعيين وضع الجلب الافتراضي على وضع الصفيف النقابي. في هذا الوضع، ستعيد طريقة الجلب () مصفوفة ترابطية بأسماء الأعمدة كمفاتيح، مما يوفر طريقة أكثر أمانًا وملاءمة لاسترداد البيانات من مجموعات النتائج.

تم ضبط PDO::ATTR_PERSISTENT على false. تعمل هذه السمة على تعطيل الاتصالات المستمرة، مما يضمن أن كل طلب جديد إلى قاعدة البيانات سينشئ اتصالاً جديدًا. من المحتمل أن تؤدي الاتصالات المستمرة إلى ثغرات أمنية، لذا يوصى بتعطيلها في معظم الحالات.

من خلال تضمين سمات الأمان الإضافية هذه، يمكنك تحسين أمان وموثوقية اتصال قاعدة البيانات المستندة إلى PDO في برنامج PHP النصي. -->