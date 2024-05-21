<p align="center">
	<img src="./img/logo.png" style="margin: 0 auto;">
</p>

## About:

This is a plugin that blocks players who use **VPN/Proxy** on their PocketMine **API 5** (PMMP 5) server

## How to use? 

To start using, you need to create an account **(Free/Premium)** at: [**https://vpnapi.io/**](https://vpnapi.io/)

After creating the account, copy the generated key in the dashboard

After copying this key, install the plugin on your server and use the command below:

- `/antivpn setkey <key>` - the `<key>` value, must be your account key generated in the **Dashboard** of **https://vpnapi.io/**

After that your plugin will be working to expel players who try to use VPN 

## Features:

### config.yml:

- `enable-cache` - If true, the system will save all IP addresses with and without proxy that enter the server, for faster checking (without connecting to the API) **RECOMENDED**
- `alert-admins` - If true, it will **alert** all players with the permission: `antivpn.alert.receive`
- `alert-admin-message` - The message that will be sent
- `kick-screen-message` - The message that will be sent on the screen of the player expelled due to suspected VPN/Proxy

### Command `/antivpn`

- `/antivpn` 
  - `setkey <key>`: Set your account key (from https://vpnapi.io/)
  - `whitelist` 
    - `add`: Add players who will be ignored by the system
    - `remove`: Remove players who are being ignored
    - `list`: View the list of players who are being ignored 

### UI 

The whitelist system is configurable via the **UI** (when you execute the command from the game and not from the console)

To access the **UI** just use: `/antivpn whitelist`

### Bypass 

- ⚠️ Players who have permission: `antivpn.bypass` will be ignored just like the **whitelist**

## Author:

- **Rajador**:
  - ✉ **Discord**: [**My Group**](https://discord.gg/DV5DgDSq7W)
  - 📷 **Instagram**: [**My Instagram**](https://www.instagram.com/rajadortv/)
  - 📽 **YouTube**: [**Channel**](https://www.youtube.com/channel/UC1UJFxth-YRkNuLBqBYyqbA)