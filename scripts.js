$(document).ready(function() {
    // Função para atualizar a lista de projetos
    function updateProjectList() {
        $.ajax({
            type: "GET",
            url: "get_projects.php",
            dataType: "json",
            success: function(response) {
                $("#project-list").empty();
                response.forEach(function(project) {
                    $("#project-list").append(
                        "<tr>" +
                        "<td>" + project.project + "</td>" +
                        "<td>" + project.status + "</td>" +
                        "<td>" + getUsername(project.user_id) + "</td>" +
                        "<td>" + getUsername(project.last_user) + "</td>" +
                        "<td>" +
                        (project.status === "available" ?
                            ("<button class='btn btn-success start-deploy-button' data-project='" + project.project + "'>Iniciar Deploy</button>") :
                            ("<button class='btn btn-primary complete-deploy-button' data-project='" + project.project + "'>Concluir Deploy</button>")
                        ) +
                        "</td>" +
                        "</tr>"
                    );
                });
            }
        });
    }

    // Função para obter o nome de usuário com base no ID
    function getUsername(user_id) {
        var username = "Desconhecido";
        $.ajax({
            type: "GET",
            async: false,
            url: "get_username.php",
            data: { user_id: user_id },
            dataType: "json",
            success: function(response) {
                username = response.username;
            }
        });
        return username;
    }

    // Atualizar a lista de projetos ao carregar a página
    updateProjectList();

    // Tratamento de clique no botão de exclusão
    $(document).on('click', '.delete-project-button', function() {
        var project = $(this).data('project');
        console.log('Clique no botão de exclusão para o projeto:', project);
        // Exibir confirmação de exclusão
        var shouldDelete = confirm('Tem certeza que deseja excluir o projeto ' + project + '?');
        
        if (shouldDelete) {
            // Realizar ação de exclusão
            $.ajax({
                url: 'delete_project.php',
                method: 'POST',
                data: { project: project },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Recarregar a página para atualizar a lista de projetos
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Ocorreu um erro ao excluir o projeto.');
                }
            });
        }
    });

    // Exibir o formulário de adicionar projeto ao clicar no botão
    $("#add-project-button").click(function() {
        $("#add-project-form").show();
    });

    // Enviar requisição para adicionar projeto
    $("#project-form").submit(function(event) {
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: "add_project.php",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Erro ao adicionar projeto. Por favor, tente novamente.");
            }
        });
    });

    // Iniciar deploy ao clicar no botão "Iniciar Deploy"
    $(document).on("click", ".start-deploy-button", function() {
        var project = $(this).data("project");
        $.ajax({
            type: "POST",
            url: "start_deploy.php",
            data: { project: project },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    updateProjectList();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Erro ao iniciar o deploy. Por favor, tente novamente.");
            }
        });
    });

    // Concluir deploy ao clicar no botão "Concluir Deploy"
    $(document).on("click", ".complete-deploy-button", function() {
        var project = $(this).data("project");
        $.ajax({
            type: "POST",
            url: "complete_deploy.php",
            data: { project: project },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    updateProjectList();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Erro ao concluir o deploy. Por favor, tente novamente.");
            }
        });
    });
});
