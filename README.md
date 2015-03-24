JsonAnnotationBundle
=================

The JsonAnnotationBundle permits to use an annotation Json for your controller

# Usage

Use the annotation @Json() in your controller

#Configuration

Some parameters are optionnals: 

		json_annotation:
				exception_code: 500 #the http code used for the exception 
            	data_key: "data" # the key used to contains the data, it can be directly at the root, using the "" parameter
            	exception_message_key: "message" #the key for the exeception message            
            	success_key: "success" #the key for the success (that is true is the result is ok, false for an exception)
            	post_query_back: false #do we send back the post parameters
            	post_query_key: "query" #the key for the post back parameters
            


## The reponse

### The normal response
It is a json stream with the property 'success' with the true value and the property 'data' containing the array returned in the controller
### The exception response
It is a json stream with the property 'success' with the false value and the property 'message' containing the error 
# Examples
## Import the bundle using composer
    "tbn/json-annotation-bundle": "dev-master"
## Import the bundle in your AppKernel
    new tbn\JsonAnnotationBundle\JsonAnnotationBundle()
## The normal response Example

    use tbn\JsonAnnotationBundle\Configuration\Json;
 
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

 use tbn\JsonAnnotationBundle\Configuration\Json;
 
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


# Events

A pre-hook event is dispatched at the beginning of the json response. It can be used to validate a token for example.

    some_bundle.listener.json_token_validation_listener:
        class: "some_bundle\\Listener\\JsonTokenValidationListener"
        tags:
            - { name: kernel.event_listener, event: json.pre_hook, method: onJsonPrehook }

The method has one argument of type JsonPreHookEvent.

	public function onJsonPrehook(JsonPreHookEvent $jsonPreHookEvent) 