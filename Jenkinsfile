pipeline {
    agent any

    environment {
        DOCKER_HUB_CREDENTIALS_ID = 'Dockerhub'
        DOCKER_HUB_USERNAME = 'lourash'
        REPO_NAME = 'php-todo'
    }

    stages {
        stage('Checkout') {
            steps {
                script {
                    git branch: 'main', url: 'https://github.com/lourash-hub/php-todo.git'
                }
            }
        }
        stage('Build Docker Image') {
            steps {
                script {
                    def branchName = env.BRANCH_NAME
                    def version = '001' // Define your version here or dynamically obtain it

                    // Define image tag with branch prefix
                    def imageTag = "${DOCKER_HUB_USERNAME}/${REPO_NAME}:${branchName}-${version}"

                    // Build Docker image
                    sh "docker build -t ${imageTag} ."
                }
            }
        }
        stage('Test DockerImage') {
            steps {
                script {
                    def branchName = env.BRANCH_NAME
                    def version = '001'
                    def imageTag = "${DOCKER_HUB_USERNAME}/${REPO_NAME}:${branchName}-${version}"
                    // Run the container
                    sh "docker run -d --name test_container -p 5000:80 ${imageTag}"

                    // Wait for a few secomds to ensure the container is up 
                    sh "sleep 10"

                    //Test the HTTP endpoint to ensure it retunrs status code 200
                    sh """
                    STATUS_CODE=\$(curl -o /dev/null -s -w "%{http_code}" http://localhost:5000)
                    if [ "\$STATUS_CODE" -ne 200 ]; then
                       echo "HTTP endpoint returned status code \$STATUS_CODE"
                          exit 1
                          fi 
                          """

                          //Clean up the test container
                          sh "docker rm -f test_container"

                }
            }
        }


        
        stage('Push Docker Image') {
            steps {
                script {
                    def branchName = env.BRANCH_NAME
                    def version = '001'
                    def imageTag = "${DOCKER_HUB_USERNAME}/${REPO_NAME}:${branchName}-${version}"
                    withCredentials([usernamePassword(credentialsId: env.DOCKER_HUB_CREDENTIALS_ID, passwordVariable: 'DOCKER_HUB_PASSWORD', usernameVariable: 'DOCKER_HUB_USERNAME')]) {
                        sh "echo ${DOCKER_HUB_PASSWORD} | docker login -u ${DOCKER_HUB_USERNAME} --password-stdin"
                    }
                    sh "docker push ${imageTag}"
                }
            }
        }
    }
    post {
        always {
            // Cleanup Docker environment
            sh 'docker system prune -f'
        }
    }
}
