=== WordPress REST API Authentication ===
Contributors: cyberlord92
Tags: api, jwt, token, REST, rest-api, json web token, oauth, protect-api, REST endpoints, secure api, api key auth, basic auth
Requires at least: 3.0.1
Tested up to: 5.8.2
Stable tag: 1.6.5
Requires PHP: 5.6
License: MIT/Expat
License URI: https://docs.miniorange.com/mit-license

Protect your WordPress REST APIs from public or unauthorized access. Secure it with WordPress REST API Authentication. [24/7 SUPPORT]


== Description ==

WordPress REST API Authentication secures rest API access for unauthorized users or protects WP REST API endpoints from public access using API Key Authentication, JWT Token Authentication, Basic Authentication, OAuth 2.0 Authentication or Third Party OAuth 2.0/OIDC/Firebase provider's Token authentication Methods. 
Also, It allows you to log in and register to WordPress REST APIs using any authentication method from the other applications like mobile, desktop application etc.

You can protect api with ease and in a highly secure way using this plugin. It also extends the JWT(JSON Web tokens) authentication to validate the REST APIs access based on the JWT token. If the JWT validation is successful, it will allow accessing the requested resource else if the JWT validation is unsuccessful then it will not allow accessing the requested resource.

The plugin also provides the feature for authentication of custom-developed REST endpoints and third-party plugin REST API endpoints like that of Woocommerce, Learndash, Buddypress, Gravity forms etc.

= Usecases =

* Block the public access to your WordPress REST APIs like /pages, /posts such that all the WordPress REST API endpoints are protected. Protect api endpoints of your WordPress site from being accessed publicly. 
* Authorize the API access for all users with authentication methods provided in the plugin
* Secure/protect third-party plugin APIs like Woocommerce, Buddypress, GravityForms, Learndash, Cocoart etc. such that these endpoints are protected and require validation to access them.
* Authenticate/Protect/Secure WordPress REST API endpoints with the access token / jwt token from other IDPs(OAuth/OIDC providers)
* Securely Login and register into Mobile or other client applications using REST APIs
* Obtain user based JWT token to use as authentication source to login and register in other platforms.
* Authenticate Woocommerce REST API endpoints by bypassing WooCommerce consumers credentials security and instead of using own authentication methods to control the data access and thus improving security and removing chances for exposing the WC credentials.
* Authenticate/secure WordPress REST APIs access using Firebase JWT token, any external JWT token, any OAuth 2.0/OpenID Connect(OIDC) provider access/id-token like Azure AD, Azure B2C, Okta, Keycloak, ADFS, AWS Cognito etc or that provided by Social login providers like Google, Facebook, Apple.

The plugin provides an interface for applications to interact with your WordPress REST API endpoints by sending and receiving data as JSON (JavaScript Object Notation) objects. Also, It provides a user-friendly user interface of the plugin to configure the methods and implement them very easily. You can easily secure/protect your WordPress REST API endpoints with ease.

There are multiple ways to secure REST API endpoints e.g. basic auth, OAuth 2.0, JWT token, API key etc. but one thing is sure that RESTful APIs should be stateless – so request authentication/authorization should not depend on cookies or sessions. Instead, each API request should come with some sort of authentication credentials(token or key) that must be validated on the server for every request.

== REST API Authentication Methods ==

= Basic Authentication =

*	If you want to protect/secure your WP REST APIs(eg. posts, pages and other REST APIs) with users login credentials or client-id:client-secret, then you can opt for the basic authentication method. It is recommended that you should use this method on HTTPS or secure socket layer.

*   Username & Password Authentication - This method for Basic Authentication authenticates the REST APIs by using username and passwords in the authorization header with the form of base64 encoded or with highly secure HMAC encryption.

*	Client-ID & Client-Secret Authentication - This method for Basic Authentication authenticates/protects the REST APIs by using client credentials in the authorization header with the form of base64 encoded or highly secure HMAC encryption. Client credentials are provided by the plugin itself. So, this method will be helpful if you don't want to pass the user credentials with the API endpoints request, hence your WP credentials would not be exposed.

= API Key Authentication (Authentication with Readonly Generated API Key or API token ) =

*	If you want to protect your WP REST API endpoints (eg. post, pages and other REST APIs) from unauthenticated users but you don’t want to share users login credentials or client id, the secret to authenticate the REST API, then you can use API Key authentication, which will generate a random authentication key for you. Using this API key/token, you can authenticate any REST API on your site. This API key(token) can be regenerated directly on the plugin's User Interface which will result in invalidation of the API key generated earlier. Hence, security is not compromised.
This method is the most simple and easy to use due to just using an API key/token to unlock API resources.

= JWT Authentication / JSON Web Tokens Authentication =

*	If you are looking to protect your REST APIs using the user based JWT token and if you do not have any third-party provider/identity provider that issues the JWT token, then you should go for the JWT Authentication method. In this case, our WordPress REST API Authentication itself issues the JWT token and works as an API Authenticator to protect your REST APIs. The plugin itself provides the REST API endpoint through which you can generate the JWT token very easily by passing the valid WordPress user credentials. Once the JWT token is generated successfully, it can be passed along with the WordPress REST API endpoint request. 
If the JWT validation is done successfully, it will allow accessing the requested resource else if the JWT validation is unsuccessful then it will not allow accessing the requested resource.
All the WP REST API endpoints which require user permission to access the resource can be handled using this JWT token and also data access can be controlled as per the user capabilities.

= OAuth 2.0 Authentication =

*	If you are looking for protecting your REST APIs using the access token and at the same time you do not have any third-party provider/identity provider, then you should go for OAuth 2.0 Authentication method. In this scenario, our WordPress REST API Authentication works as both OAuth Server(Provider) and API Authenticator to protect your REST APIs. It is the most secure method to authenticate the WordPress REST API endpoints. The plugin supports 2 types of grants for the OAuth 2.0 flow:

*   Client Credentials Grant - This method uses the OAuth 2.0 protocol with Client Credentials grant to authenticate the WP REST API endpoints, the plugin will provide a time-based token based on the client credentials authentication and you can use it to register a user into WordPress by passing the token in the authorization header of user create API.

*   Password Grant - This method uses the OAuth 2.0 protocol with Password grant to authenticate the REST APIs, the plugin will provide a time-based token based on the user credentials authentication and you can use it to log in into WordPress by passing the token into the authorization header of every API request.

This authentication is the most secure authentication method and features like token expiration, refresh token generation gives more security and control for API access.

= API Authentication for Third Party OAuth 2.0 Provider( using Introspection Endpoint / User Info Endpoint or JWKS URL) =

*	If you are looking for protecting/restrict access to your WP REST APIs using your OAuth Provider/Identity provider, then you should go for Third Party Provider Authentication method. It would be helpful to authenticate the WordPress REST APIs with different platforms tokens like the access or JWT token of Google, AWS Cognito, Auth0, miniOrange, firebase, Amazon, Apple, Facebook, Okta etc.
So, if you are already using an external OAuth/OpenID Connect (Identity provider) which provides you with an access token/id token or a JWT token, then that token can be used to authenticate the WordPress REST APIs and the plugin will validate the token directly from these token providers and only on successful validation, API endpoints are allowed to access. 


== FEATURES ==

FREE PLAN 

* Supports Basic Authentication with username and password along with base64 encoding of the API token.
* JWT Token-based Authentication for WP API endpoints.
* Authentication for all the Standard WordPress REST API endpoints.
* Allow or Deny public access to your WordPress standard REST APIs as per your requirement.
* Token endpoint to retrieve user-specific JWT Token.
* Restrict non-logged-in users to access REST API endpoints.
* Postman Samples for each Authentication method to test the APIs access with the Postman application

PREMIUM PLANS

* Authentication(Protection) for all WordPress REST API endpoints including standard WP REST APIs and custom/third-party plugin's REST API endpoints.
* Supports Basic Authentication (both WP User credentials and Client credentials), JWT Token Authentication, API Key Authentication, OAuth 2.0 Authentication, Third-Party OAuth 2.0/OIDC Provider's Token Authentication methods.
* HMAC encryption & User-specific Client credentials with Basic authentication.
* Token endpoint to retrieve user-specific JWT Token.
* Allow or Deny public access to all the WordPress standard REST APIs as well as custom/third-party plugin's REST API endpoints as per requirement.
* Universal(Global) API key as well as User-specific API key for authentication.
* Supports JWT Authentication with signature validation using highly secured HSA & RSA Signing.
* Custom Token Expiry feature for JWT token to further increase security.
* Provides the Time based Access token or JWT token.
* WordPress Login using Access token or JWT token.
* Authenticate WordPress REST APIs with the token (access token / jwt token) provided by your OAuth/OIDC Provider ( Third Party Provider ) like Azure, AWS Cognito, ADFS, Keycloak, Google, Facebook, ADFS, Firebase or any external JWT provider etc.
* User's WordPress Role & Capability-based access to all the WordPress REST API endpoints like posts, pages etc.
* Allow or Deny public access to your WordPress REST APIs as per requirement.
* Custom Header support rather than just 'Authorization' to increase security.
* Create users in WordPress based on third-party provider's access tokens(JWT tokens).
* Feature to exclude any selected REST API endpoints.
* Restrict non-logged-in users to access REST API endpoints.


== INTEGRATION ==

= WooCommerce API =

*	This plugin supports WordPress REST API Integration with the WooCommerce REST APIs. You can easily protect api of the Woocoomerce in a highly secure way with this. You can authenticate the WooCommerce store APIs with your mobile or desktop application & extend the features and functionality of your eCommerce store.

= BuddyPress API ( BP REST API ) =

*	This plugin supports BuddyPress API integration with WordPress REST APIs. You can access BP REST API endpoints and also authenticate those from different Authentication methods like JWT token, API Keys etc.

= Gravity Form API =

*	The plugin supports interaction with Gravity Forms from an external client application. WP REST API also allows WordPress users to create, read, update and delete forms, entries, and results over HTTP based on their roles.

= Learndash API =

*	The plugin allows accessing LearnDash API from a mobile app or any external application. It provides you secure access to Learndash user profiles, courses, groups & many more APIs.

= Custom Built REST API Endpoints =

*   The plugin supports authentication for your own built custom REST API routes/endpoints. You can secure these API endpoints using the plugin's highly secured authentication methods.


= External/Third-party plugin API endpoints integration in WordPress = 

* The plugin also provides the feature to integrate external/third-party(Non-WordPress) REST API endpoints into the WordPress as a separate add-on such this external API integration can be done with the third-party plugin forms like that provided by Elementor, Woocommerce, Gravity forms, WPforms etc such that these external APIs can be called on these form submission events. These external API integrations are also compatible with third-party plugin's payment gateway like that provided by Wooocmerce, Wpforms, Stripe, n-genius, Paypal payment gateway and many more.

* These integrations can be used to fetch/update the data from the third-party side into the WordPress that can be used to display it in WordPress site as well as this data can be processed further to use with any other plugin or WordPress events. 

== Installation ==

This section describes how to install the WordPress REST API Authentication and get it working.

= From your WordPress dashboard =

1. Visit `Plugins > Add New`
2. Search for `REST API Authentication`. Find and Install `api authentication` plugin by miniOrange
3. Activate the plugin

= From WordPress.org =

1. Download WordPress REST API Authentication.
2. Unzip and upload the `wp-rest-api-authentication` directory to your `/wp-content/plugins/` directory.
3. Activate WordPress REST API Authentication from your Plugins page.


== Privacy ==

This plugin does not store any user data. 

== Frequently Asked Questions ==

= What is the use of API Authentication =
    The REST API authentication prevents unauthorized access to your WordPress APIs. It reduces potential attack factors.
	
= How to enable API access in WooCommerce?
    You can enable API access in WooCommerce using our WP REST API Authentication plugin. Please reach out to us at oauthsupport@xecurify.com.

= How does the REST API Authentication plugin work? =
	You just have to select your Authentication Method.
	Based on the method you have selected you will get the authorization code/token after sending the token request.
	Access your REST API with the code/token you received in the previous step. 

= How to access draft posts? =
	You can access draft posts using Basic Auth, OAuth 2.0(using Username: Password), JWT authentication, API Key auth(using Universal Key) methods. Pages/posts need to access with the status. Default status used in the request is 'Publish' and any user can access the Published post. 
	To access the pages/posts stored in the draft, you need to append the ?status=draft to the page/post request.
	For Example:
	You need to use below URL format while sending request to access different type of posts
	1. Access draft posts only
		https://<domain>/wp-json/wp/v2/posts?status=draft
	2. Access all type of posts
		https://<domain>/wp-json/wp/v2/posts?status=any
	You just have to change the status(draft, pending, any, publish) as per your requirement. You do not have to pass status parameter to access Published posts.

= How can I authenticate the REST APIs using this plugin? =
	This plugin supports 5 methods: i) authentication through API key or token, ii) authentication through user credentials passed as an encrypted token, iii) authentication through JWT (JSON Web token), iv) authentication through OAuth 2.0 protocol and v) authentication via JWT token obtained from the external OAuth/OpenId providers which include Google, Facebook, Azure, AWS Cognito etc. 


= Does this plugin allows authentication through JWT (JSON Web Tokens)? =
	Yes, this plugin supports the REST API authentication through JWT (JSON Web token). The JWT be validated every time an API request is made, only the requested resource for the API call be allowed to access if the JWT validation is successful.

= How do I authenticate WordPress REST API endpoints using external JWT token or access token provided by OAuth/OIDC/Social Login providers? = 
     This plugin provides you with an authentication method called the 'Third Party Provider' authentication method in which the JWT token or access token obtained from external identities(OAuth/OIDC providers) like Firebase, Okta, Azure, Keycloak, ADFS, AWS Cognito, Google, Facebook, Apple etc. can be passed along with API request in the header and the plugin validates that JWT / access token directly from these external sources/providers. 

= How do I access user-specific data for Woocommerce REST API without the need to pass actual Woocommerce API credentials? =
	This plugin provides a way to bypass Woocommerce security and instead authenticate APIs using the authentication methods, hence improvising the security and hence no chance of Woocommerce credentials getting compromised. The authentication token passed in the API request will validate the user and results into user-specific data only. For more information, please contact us at oauthsupport@xecurify.com


== Screenshots ==

1. List of API Authentication Methods
2. List of Protected WP REST APIs
3. Advanced Settings
4. Custom API Integration

== Changelog ==

= 1.6.5 =
* WordPress 5.8.2 compatiblity
* UI Changes

= 1.6.4 =
* Security Improvements

= 1.6.3 =
* WordsPress 5.8.1 compatability
* Readme Updates 

= 1.6.2 =
* WordPress 5.8 compatiblity
* Bug Fixes
* Usability Improvements
* UI Updates

= 1.6.1 =
* Bug Fixes
* Modifications for Custom API auth capabilities

= 1.6.0 =
* Minor fixes
* UI updates
* Usability improvements

= 1.5.2 =
* Minor fixes
* Remove extra code

= 1.5.1 =
* Minor fixes
* Security fixes

= 1.5.0 =
* Minor fixes
* Security fixes

= 1.4.2 =
* UI updates

= 1.4.1 =
* UI updates
* Minor fixes

= 1.4.0 =
* WordPress 5.6 compatibility

= 1.3.10 =
* Allow all REST APIs to authenticate
* Added postman samples
* Minor Bugfix

= 1.3.9 =
* Minor Bugfix

= 1.3.8 =
* Added compatibility for WP 5.5

= 1.3.7 =
* Bundle plan release
* Minor Bugfix

= 1.3.6 =
* Added compatibility for WP 5.4

= 1.3.5 =
* Minor Bugfix

= 1.3.4 =
* Minor Bugfix

= 1.3.2 =
* Minor Bugfix

= 1.3.1 =
* Minor Fixes

= 1.3.0 =
* Added UI Changes
* Updated plugin licensing
* Added New features
* Added compatibility for WP 5.3 & PHP7.4
* Minor UI & feature fixes

= 1.2.1 =
* Added fixes for undefined getallheaders()

= 1.2.0 =
* Added UI changes for Signing Algorithms and Role-Based Access
* Added Signature Validation
* Minor fixes

= 1.1.2 =
* Added JWT Authentication
* Fixed role-based access to REST APIs
* Fixed common class conflicts

= 1.1.1 =
* Fixes to Create, Posts, Update Publish Posts

= 1.1.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2
* Added support for accessing draft posts as per User's WordPress Role Capability
* Allowed Logged In Users to access posts through /wp-admin Dashboard

= 1.0.2 =
* Added Bug fixes  

= 1.0.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2

== Upgrade Notice ==

= 1.1.1 =
* Fixes to Create, Posts, Update Publish Posts

= 1.1.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2
* Added support for accessing draft posts as per User's WordPress Role Capability
* Allowed Logged In Users to access posts through /wp-admin Dashboard

= 1.0.2 =
* Added Bug fixes  

= 1.0.0 =
* Updated UI and features
* Added compatibility for WordPress version 5.2.2