#!/bin/bash

aws rds create-db-instance --db-name db1 --db-instance-identifier itmo-544-mp1 --allocated-storage 20 --db-instance-class db.t2.micro --engine mysql --master-username aerande --master-user-password ilovebunnies --availability-zone us-west-2a

aws rds wait db-instance-available --db-instance-identifier itmo-544-mp1

aws s3 mb s3://rawimagesbucket --region us-west-2
aws s3 mb s3://finishedimagesbucket --region us-west-2

aws ec2 run-instances --count $1 --image-id ami-6e1a0117 --key-name $2 --security-group-ids $3 --instance-type t2.micro --iam-instance-profile Name=$4 --user-data file://install-app-env.sh

cloud=`aws ec2 describe-instances  --query 'Reservations[*].Instances[].InstanceId' --filters "Name=instance-state-name, Values=pending" --output text`

aws ec2 wait instance-running --instance-ids $cloud

aws elb create-load-balancer --load-balancer-name itmo-544-test --listeners "Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80" --availability-zones us-west-2a --security-groups $3

aws elb register-instances-with-load-balancer --load-balancer-name itmo-544-test --instances $cloud

aws elb create-lb-cookie-stickiness-policy --load-balancer-name itmo-544-test --policy-name enable-stickiness-cookie-policy --cookie-expiration-period 60

aws elb set-load-balancer-policies-of-listener --load-balancer-name itmo-544-test --load-balancer-port 80 --policy-names enable-stickiness-cookie-policy

aws autoscaling create-launch-configuration --launch-configuration-name $5 --image-id ami-6e1a0117 --key-name $2 --instance-type t2.micro --user-data file://install-app-env.sh --security-groups $3 --iam-instance-profile $4

aws autoscaling create-auto-scaling-group --auto-scaling-group-name itmo-544-as --launch-configuration-name $5 --availability-zones us-west-2a --min-size 0 --max-size 5 --desired-capacity 1

aws autoscaling attach-instances --instance-ids $cloud --auto-scaling-group-name itmo-544-as

aws autoscaling attach-load-balancers --load-balancer-names itmo-544-test --auto-scaling-group-name itmo-544-as