# Check Service Health Status

Returns service health status info along with availability statuses of external dependencies.

**URL** : `/api/v1/health`

**Method** : `GET`

**Token auth required** : YES

## Success Response

**Code** : `200 OK`

**Content examples**

Good health response.

```json
{  
   "health":"OK",
   "services":{  
      "db":true,
      "redis":true,
      "yandex":true,
      "ipstack":true
   }
}
```

Health problems response.

```json
{  
   "health":"PROBLEMS",
   "services":{  
      "db":true,
      "redis":true,
      "yandex":true,
      "ipstack":true
   }
}
```

## Notes

* If the User does not have a `UserInfo` instance when requested then one will
  be created for them.