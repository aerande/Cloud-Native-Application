1. Copy all the shell scripts described below into the shared folder of the vagrant box and copy all the shell script files into the '/home/vagrant' directory:
  - create-env.sh
  - destroy-env.sh
  - install-app-env.sh

2. Run the 'create-env.sh' file to create environment with following parameters:
  - $1 is count for number of instances
  - $2 is key-pair name
  - $3 is security-group ID
  - $4 is IAM instance profile name
  - $5 is desired launch configuration name

3. Note the Public DNS of any instance, copy the link and open it in the browser.

4. Run the file '<Public DNS>/index.php'.

5. To destroy all the environment, run the 'destroy-env.sh' file with the following parameters:
  - $1 is given launch configuration name while creating