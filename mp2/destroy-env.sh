#!/bin/bash

aws autoscaling detach-load-balancers --auto-scaling-group-name itmo-544-as --load-balancer-name itmo-544-test

aws autoscaling delete-auto-scaling-group --auto-scaling-group-name itmo-544-as --force-delete

aws autoscaling delete-launch-configuration --launch-configuration-name $1

cloud=`aws ec2 describe-instances  --query 'Reservations[*].Instances[].InstanceId' --filters "Name=instance-state-name, Values=pending" --output text`

aws elb deregister-instances-from-load-balancer --load-balancer-name itmo-544-test --instances $cloud

aws elb delete-load-balancer-listeners --load-balancer-name itmo-544-test --load-balancer-ports 80

aws elb delete-load-balancer --load-balancer-name itmo-544-test

aws ec2 terminate-instances --instance-ids $cloud

aws rds delete-db-instance --db-instance-identifier itmo-544-mp1 --skip-final-snapshot

aws rds wait db-instance-deleted --db-instance-identifier itmo-544-mp1

aws s3 rb s3://rawimagesbucket --force
aws s3 rb s3://finishedimagesbucket --force