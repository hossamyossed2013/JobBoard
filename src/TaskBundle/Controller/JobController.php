<?php

namespace TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use TaskBundle\Repository\JobRepository;
use TaskBundle\UserPost\UserPostRequest;

class JobController
{
    private $templating;
    private $jobRepository;
    private $userPostRequest;
    private $request;

    public function __construct(EngineInterface $templating,
            Request $request,
            UserPostRequest $userPostRequest,
            JobRepository $jobRepository
            )
    {
        $this->templating = $templating;
        $this->request = $request;
        $this->userPostRequest = $userPostRequest;
        $this->jobRepository = $jobRepository;
    }
    
    public function index()
    {
        $jobs = $this->jobRepository->getJobPosts();
        
        return $this->templating->renderResponse(
            'TaskBundle:Job:index.html.twig',
            array('entities' => $jobs)  
        );   
    }   
    
    public function newPost()
    {
        return $this->templating->renderResponse(
            'TaskBundle:Job:new.html.twig'            
        );   
    }   
    
    public function createPost()
    {
        $postInputs = array('email' => $this->request->get('email'),
            'title' => $this->request->get('title'),
            'description' => $this->request->get('description')
            );
        
        $job = $this->jobRepository->setJobPost($postInputs);
        
        $response = $this->userPostRequest->proceed($job);
                
        return $this->viewMsg($response);
        
    }
    
    public function approvePost($id)
    {
        $response = $this->jobRepository->approvePost($id);
        
        return $this->viewMsg($response);

    }

    public function rejectPost($id)
    {
        $response = $this->jobRepository->rejectPost($id);
        
        return $this->viewMsg($response);

    }

    public function viewMsg($msg)
    {        
        return $this->templating->renderResponse(
            'TaskBundle:Job:result.html.twig',
            array('msg' => $msg)  
        );
    }

}
