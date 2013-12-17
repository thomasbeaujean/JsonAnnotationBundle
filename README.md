JsonAnnotationBundle
=================

The JsonAnnotationBundle permits to use an annotation Json for your controller

# Usage

Use the annotation @Json() in your controller

## The reponse
### The normal response
It is a json stream with the property 'success' with the true value and the property 'data' containing the array returned in the controller
### The exception response
It is a json stream with the property 'success' with the false value and the property 'message' containing the error 
# Examples
## The normal response Example

    use thomasbeaujean\JsonAnnotationBundle\Configuration\Json;
 
    class DefaultController extends Controller
    {
 
         /**
          * The main view
          *
          * @Route("/someroute")
          * @Json()
          *
          * @return array
          */
         public function somerouteAction()
         { 
  	         return array('data1' => 'value1', 'data2' => 'value2');
         }
      }

It will send back a json stream

     'success' => true
     'data'    => ['data1' => 'value1', 'data2' => 'value2']

## The exception response

 use thomasbeaujean\JsonAnnotationBundle\Configuration\Json;
 
     class DefaultController extends Controller
     {
         /**
          * The main view
          *
          * @Route("/someroute")
          * @Json()
          *
          * @return array
          */
         public function somerouteAction()
         { 
    	     throw \Exception('some error occured');
         }
     }

It will send back a json stream

     'success' => false
     'message'    => 'some error occured'

