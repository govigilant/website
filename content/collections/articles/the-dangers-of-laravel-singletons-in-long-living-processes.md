---
id: 43cd55e1-aad6-458a-a2b4-433755c6c5c3
blueprint: article
title: 'Laravel Singletons Can Be Dangerous in Long Living Processes'
introduction: "Singletons are a great feature of Laravel's service container but users of the framework must be aware that context aware singletons can lead to unexpected behaviour in an application when using long living processes."
author: 1
updated_by: 1
updated_at: 1726255192
published_at: '2024-09-13'
content:
  -
    type: paragraph
    content:
      -
        type: text
        text: "Vigilant relies heavily on long-living processes, it uses Octane for the handling of HTTP requests and Horizon for handling jobs. Vigilant is an application with a lot of background tasks. It is designed to have multiple tenants in one database so it is crucial that data is handled securely and that tenants do not see each other's data. Vigilant makes use of scopes to do this. These scopes all talk to a single singleton, the "
      -
        type: text
        marks:
          -
            type: code
        text: TeamService
      -
        type: text
        text: ' which looks something like this:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          class TeamService
          {
              protected ?Team $team = null;

              public function fromAuth(): void
              {
                  // Retrieve logged in user and set team
              }

              public function setTeamById(int $teamId): void
              {
                  // Find team by id and set
              }

              public function team(): ?Team
              {
                  if ($this->team === null) {
                      $this->fromAuth();
                  }

                  return $this->team;
              }
          }
  -
    type: paragraph
    content:
      -
        type: text
        text: 'This service class is then bound as a singleton: '
      -
        type: text
        marks:
          -
            type: code
        text: '$this->app->singleton(TeamService::class);'
      -
        type: hardBreak
      -
        type: text
        text: 'A middleware will set the team for the entire application:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          class TeamMiddleware
          {
              public function __construct(protected TeamService $teamService) {}

              public function handle(Request $request, Closure $next): Response
              {
                  $this->teamService->fromAuth();

                  return $next($request);
              }
          } 
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The scopes can then get an instance of the '
      -
        type: text
        marks:
          -
            type: code
        text: TeamService
      -
        type: text
        text: ' and add the where query to filter data.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'In a traditional PHP application, the entire app is built and destroyed on each request. That means that the singleton only lives for that request.'
  -
    type: heading
    attrs:
      level: 3
    content:
      -
        type: text
        text: 'But how do long-living processes handle this?'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'This is where singletons become tricky. The singleton will get created when the process starts and will stay there until it is destroyed. For singletons that do not store any data, this is fine but when a singleton stores data like our '
      -
        type: text
        marks:
          -
            type: code
        text: TeamService
      -
        type: text
        text: ' it will cause issues. '
  -
    type: paragraph
    content:
      -
        type: text
        text: "Let's take a Laravel job as an example, we have a job that sets the current team. When that job is finished the team will still be set and any next jobs that run will use the team that was set from the first job."
  -
    type: paragraph
    content:
      -
        type: text
        text: 'This is '
      -
        type: text
        marks:
          -
            type: bold
        text: 'very dangerous'
      -
        type: text
        text: ' as it means that historical jobs have an impact on current jobs, which is something we do not want and do not expect.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'If a new job does not overwrite the team and it tries to retrieve something it will potentially get data it does not have access to. Or it will not have data when you expect it to have data.'
  -
    type: heading
    attrs:
      level: 3
    content:
      -
        type: text
        text: 'The correct way of handling singletons in a long-lived application'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Fortunately, Laravel has a built-in method for this called '
      -
        type: text
        marks:
          -
            type: code
        text: scoped
      -
        type: text
        text: '. Instead of using '
      -
        type: text
        marks:
          -
            type: code
        text: singleton
      -
        type: text
        text: ' to bind the class we can use '
      -
        type: text
        marks:
          -
            type: code
        text: scoped
      -
        type: text
        text: ': '
      -
        type: text
        marks:
          -
            type: code
        text: '$this->app->scoped(TeamService::class);'
  -
    type: paragraph
    content:
      -
        type: text
        text: "While the name isn't directly clear on what it does we can find this in the documentation:"
  -
    type: blockquote
    content:
      -
        type: paragraph
        content:
          -
            type: text
            text: 'The '
          -
            type: text
            marks:
              -
                type: code
            text: scoped
          -
            type: text
            text: ' method binds a class or interface into the container that should only be resolved one time within a given Laravel request/job lifecycle. While this method is similar to the '
          -
            type: text
            marks:
              -
                type: code
            text: singleton
          -
            type: text
            text: ' method, instances registered using the '
          -
            type: text
            marks:
              -
                type: code
            text: scoped
          -
            type: text
            text: ' method will be flushed whenever the Laravel application starts a new "lifecycle", such as when a '
          -
            type: text
            marks:
              -
                type: link
                attrs:
                  href: 'https://laravel.com/docs/11.x/octane'
                  rel: null
                  target: null
                  title: null
            text: 'Laravel Octane'
          -
            type: text
            text: ' worker processes a new request or when a Laravel '
          -
            type: text
            marks:
              -
                type: link
                attrs:
                  href: 'https://laravel.com/docs/11.x/queues'
                  rel: null
                  target: null
                  title: null
            text: 'queue worker'
          -
            type: text
            text: ' processes a new job.'
  -
    type: paragraph
    content:
      -
        type: text
        text: "Let's dive into the framework to see how it works. "
      -
        type: hardBreak
      -
        type: text
        text: 'First of all, it will register your class as a singleton and store it in an array of '
      -
        type: text
        marks:
          -
            type: code
        text: scopedInstances
      -
        type: text
        text: .
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          public function scoped($abstract, $concrete = null)
          {
             $this->scopedInstances[] = $abstract;

              $this->singleton($abstract, $concrete);
          }
  -
    type: paragraph
    content:
      -
        type: text
        text: 'When we look at the queue worker we can see that in the endless '
      -
        type: text
        marks:
          -
            type: code
        text: while
      -
        type: text
        text: ' loop it will reset all scopes before processing each job:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          while (true) {
               // ...
               // Reset scopes
               if (isset($this->resetScope)) {
                   ($this->resetScope)();
               }
               // Handle job
              $job = $this->getNextJob(
                   $this->manager->connection($connectionName), $queue
              );
              // ...
          }
  -
    type: paragraph
    content:
      -
        type: text
        text: 'The '
      -
        type: text
        marks:
          -
            type: code
        text: resetScope
      -
        type: text
        text: ' is a callable on the worker that gets set in the queue service provider which calls the '
      -
        type: text
        marks:
          -
            type: code
        text: forgetScopedInstances
      -
        type: text
        text: ' method on the app container. This method simply loops through the scoped instances and unsets them:'
  -
    type: codeBlock
    attrs:
      language: null
    content:
      -
        type: text
        text: |-
          public function forgetScopedInstances()
          {
             foreach ($this->scopedInstances as $scoped) {
                  unset($this->instances[$scoped]);
              }
          }
  -
    type: heading
    attrs:
      level: 3
    content:
      -
        type: text
        text: Conclusion
  -
    type: paragraph
    content:
      -
        type: text
        text: 'Singletons are great to prevent setting up classes multiple times but if your singletons store any kind of data you should use a scoped singleton to make sure that the data does not persist between requests or jobs.'
  -
    type: paragraph
    content:
      -
        type: text
        text: 'This feature was added in Laravel 8 in '
      -
        type: text
        marks:
          -
            type: link
            attrs:
              href: 'https://github.com/laravel/framework/pull/37521'
              rel: null
              target: null
              title: null
        text: '#37521'
      -
        type: text
        text: .
---
