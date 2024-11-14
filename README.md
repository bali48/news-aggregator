# News Hub Backend

This is the Backend for the NewsHub Web application. Here I am exposing some endpoints which include

-   User Authentication (SignIn, SignUp)
-   News Aggregator List
-   User Preferences Save and Get
-   Supporting Apis for News Sources, Authors and Category

## Data Sources

I Used Three Data Sources Here to fetch the News.

-   NewsApi
-   Guardian
-   NewYork Times

## How to Setup

For running the backend of the application run this command.
make sure you have installed docker already
also create .env file from .env.example

```bash
docker-compose up -d --build
```

once the container is up we can see the app running on

```bash
http://localhost:8000
```

## Author

Muhammad Bilal
