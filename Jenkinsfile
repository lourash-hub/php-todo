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
                    git branch: 'main/feature', url: 'https://github.com/lourash-hub/php-todo.git'
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
