# Core API for WHMCS

## Install

1. Upload *hdcore* folder to `modules/servers/` directory under your WHMCS installation

## Product Setup

### Module Settings
![Module Settings](https://cloud.githubusercontent.com/assets/8882975/20186516/342a070e-a725-11e6-9f94-2cbcd2b23965.png)

1. Edit each server product via the menu path **Setup > Products/Services > Products/Services**
2. Select the **Module Settings** tab and then select **Hdcore** as the Module Name
3. Enter in your public and private API keys obtained from https://core.hostdime.com/apikeys/
4. Ensure the option **Do not automatically setup this product** is selected and save changes

### Custom Fields

![Custom Fields](https://cloud.githubusercontent.com/assets/8882975/20186520/38ae7c10-a725-11e6-8e1f-3916a11cdfbb.png)

1. While still on the product edit page, select the **Custom Fields** tab
2. Add a new custom field with the name **API ID** and type of **Text Box**
3. Select **Admin Only** checkbox
3. Save changes


## Configure client services

![client service configuration](https://cloud.githubusercontent.com/assets/8882975/20186518/367cdc16-a725-11e6-85c1-c22204ec8059.png)

1. Edit the desired client's service
2. In the newly added custom field **API ID**, enter in the API ID displayed for the server in core
3. The API ID is available on the server's profile in core
4. Save changes. You will now be able to suspend, unsuspend, power off/on, and power cycle the server from within WHMCS
5. Server information and bandwidth graphs will now be displayed on the service's profile when the client views it from their WHMCS