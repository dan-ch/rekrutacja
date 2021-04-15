package uwb.licencjat.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import uwb.licencjat.model.UserRole;
import uwb.licencjat.repository.UserRolesRepository;

import java.util.List;

@Service
public class UserRoleService {

    private UserRolesRepository userRolesRepository;

    @Autowired
    public UserRoleService(UserRolesRepository userRolesRepository) {
        this.userRolesRepository = userRolesRepository;
    }

    public List<UserRole> getAllRoles(){
        return userRolesRepository.findAll();
    }
}
