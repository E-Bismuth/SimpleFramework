# SimpleFramework
Build a Simple framework with a Model helper for easing database reques.

The goal of this mini framework is to give a base than can be easily adapted to most of project and that almost all developer can use easily without hours of tutorial


Getting Started
--------------------------------------------------

Create an .htaccess file and index.php on the public foler.

Load the autoload instance (on Core/autoload.php).

Build your routes.


Routes
--------------------------------------------------
As almost all framework you build you routes by matching the url and adding a callback function or a controller "link"

You can extract parameters from the url 
Group some Routes that will be all prefixed


Model
--------------------------------------------------
The better part of this project!

It included a class name SPDO for MySQL connection to database that allow you to work easily with master/replication database

The Model class includ a lot of function that build and execute the query easily.

And one raw function where you can execute a complicate query that the model not allow you to do

By adding relation between tables, the model give you the oportunity to do some join easily


Controller
--------------------------------------------------
In developpement...
For the moment the controller just have a render function and some parameters for the view


Magic
--------------------------------------------------
There some function that help coding

Debug make a good view to see what happen on your code, you can debug a var by ending the code or not

Variables :

  1) Session take care of the $_SESSION array and can be parent of other class (like flash) so it will be easier to access some array, for getting a multidimentional array use 'Session::get("Key/field")' in place of $_SESSION["Key"]["field"].
  
  2) Flash (extended of session) fill all the given params to the session prefixed by "Flash", all this session will be available only for the next page.
  
  3) projectVars here you can add all the params(string,array,object) that you whant to use/update after in the code.
  
  4) projectDefine here you can add all the params(string,array,object) that you whant to use and never update after in the code. Look like original DEFINE of php but give you oportunity to store array and object, can be use to define a singleton too.
