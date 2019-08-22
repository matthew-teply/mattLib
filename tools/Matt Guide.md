# matt
*A tool for mattLib made by **Matyáš Teplý** 2019*

## Summary
matt is a tool for easy folder structure creation, you can easily create:
1. Apps
2. Components
3. Routes

## General command structure
matt is using a simple syntax

```shell
php matt.php <action> <type> <app name> <path> <additional flags>
```

### **Action**
There are two simple actions
1. create
2. delete

### **Type**
There are three types at the moment
1. app - Create a new app in the *apps* folder
2. component - Create a new component
3. route - Create a new route (Controller + View)

### **App name**
The name of the app you want to create stuff in

### **Path**
Path of the component/route in your app, in their respective folders, of course

## Apps
**Create**

This creates directory in the /app directory, it creates all the necessary folder, and generates a *config.php* file and an *app.php* file
```shell
php matt.php create app <name of your app>
```

**Delete**

This deletes an entire app
```shell
php matt.php delete app <name of your app>
```

## Components
**Create**

This creates a script, css and template files
```shell
php matt.php create component <app name> <path of component>
```

**Delete**
   
This deletes script, css and template files
```shell
php matt.php delete component <app name> <path of component>
```

This deletes entire component directories
```shell
php matt.php delete component <app name> <path of component folder> -dir
```

## Routes
**Create**

This creates a route, including the controller file and the view file
```shell
php matt.php create route <app name> <path of route>
```

**Delete**

This delete the controller and views files
```shell
php matt.php delete route <app name> <path of route>
```

This deletes an entire route directory
```shell
php matt.php create route <app name> <path of route> -dir
```