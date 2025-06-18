# Upgrading Tech Bench

The Tech Bench Upgrade script will download the latest Tech Bench Docker Container
and replace the existing Container.  It will also validate the Docker Compose file
to make sure that all necessary containers are in place.

Run the following commands to download and run the latest update script.

```bash
# Download the latest Update Script
wget https://raw.githubusercontent.com/butcherman/Tech_Bench/master/scripts/updateTB.sh
# Make script executable
chmod +x updateTB.sh
# Run the script
./updateTB.sh
```

This script is not actually built yet, so um yeah.......
